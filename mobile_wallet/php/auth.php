<?php
require_once 'db_singleton.php';
require_once 'user_builder.php';

// Debug: Confirm this file is loaded
error_log("auth.php loaded at " . date('Y-m-d H:i:s'));

// Handle logout request
$auth = new Auth();
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $auth->logout();
    header("Location: ../login.php");
    exit;
}

class Auth {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = Database::getInstance()->getConnection();
        error_log("Auth constructor called");
    }

    public function register($username, $password, $email, $phone, $nid) {
        // Validate password: 8+ characters, at least 1 uppercase, 1 lowercase, 1 number
        if (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password)) {
            throw new Exception("Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.");
        }

        // Validate NID: exactly 12 digits
        if (!preg_match("/^\d{12}$/", $nid)) {
            throw new Exception("NID must be exactly 12 digits.");
        }

        // Validate phone: exactly 11 digits
        if (!preg_match("/^\d{11}$/", $phone)) {
            throw new Exception("Phone number must be exactly 11 digits.");
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $userBuilder = new UserBuilder();
        $user = $userBuilder
            ->setUsername($username)
            ->setPassword($hashed_password)
            ->setEmail($email)
            ->setPhone($phone)
            ->setNid($nid)
            ->build();
        $balance = 0;

        $stmt = $this->db->prepare("INSERT INTO users (username, password, email, phone, nid, balance) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("sssssi", $user->username, $user->password, $user->email, $user->phone, $user->nid, $balance);
        error_log("bind_param called with username: " . $user->username);

        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $stmt->close();
        return $result;
    }

    public function login($phone, $password) {
        error_log("Login attempt with phone: " . $phone);
        $stmt = $this->db->prepare("SELECT id, username, password, email, phone FROM users WHERE phone = ?");
        if (!$stmt) {
            error_log("Prepare failed in login: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            error_log("User found with phone: " . $phone);
            error_log("Stored hash: " . $user['password']);
            if (password_verify($password, $user['password'])) {
                error_log("Password verified for phone: " . $phone);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['phone'] = $user['phone'];
                return true;
            } else {
                error_log("Password verification failed for phone: " . $phone);
            }
        } else {
            error_log("User not found with phone: " . $phone);
        }
        $stmt->close();
        return false;
    }

    public function verifyPassword($user_id, $password) {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $stmt->close();
            return password_verify($password, $user['password']);
        }
        $stmt->close();
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['phone']);
        error_log("User logged out at " . date('Y-m-d H:i:s'));
    }

    public function resetPassword($email, $nid, $new_password) {
        if (strlen($new_password) < 8 || !preg_match("/[A-Z]/", $new_password) || !preg_match("/[a-z]/", $new_password) || !preg_match("/[0-9]/", $new_password)) {
            return false;
        }

        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND nid = ?");
        $stmt->bind_param("ss", $email, $nid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user['id']);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        $stmt->close();
        return false;
    }
}
?>