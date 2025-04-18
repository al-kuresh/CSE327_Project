<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_singleton.php';
require_once 'user_builder.php';
require_once 'transactions.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($username, $password, $phone, $nid) {
        // Builder Pattern: Use UserBuilder to construct User object with required fields
        $builder = new UserBuilder();
        $user = $builder->setUsername($username)
                        ->setPhone($phone)
                        ->setNid($nid)
                        ->setBalance(0.00)
                        ->build();
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, phone, nid, balance) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $user->username, $hashed_password, $user->phone, $user->nid, $user->balance);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                // Fetch fresh balance using TransactionManager
                $transactionManager = new TransactionManager();
                $_SESSION['balance'] = $transactionManager->getBalance($user['id']);
                return true;
            }
        }
        return false;
    }

    public function resetPassword($nid, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE nid = ?");
        $stmt->bind_param("ss", $hashed_password, $nid);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return true;
        }
        return false;
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../login.php");
        exit;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

// Handle logout request
if (isset($_GET['logout']) && $_GET['logout'] == '1') {
    $auth = new Auth();
    $auth->logout();
}
?>