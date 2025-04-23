<?php
require_once 'php/auth.php';
$auth = new Auth();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']); // Trim username to avoid spaces
    $password = $_POST['password']; // Password will be hashed, so no trimming needed
    $phone = trim($_POST['phone']); // Trim phone
    $nid = trim($_POST['nid']); // Trim NID to remove leading/trailing spaces
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    // Debug: Log the exact NID value to see if there are hidden characters
    error_log("NID input: '$nid' (length: " . strlen($nid) . ")");

    // Validate phone (11 digits)
    if (!preg_match('/^\d{11}$/', $phone)) {
        $message = "Phone number must be exactly 11 digits!";
    }
    // Validate NID (must be exactly 12 digits, only digits allowed)
    elseif (!preg_match('/^\d{12}$/', $nid)) {
        // Additional check to inform user about non-digit characters
        if (!ctype_digit($nid)) {
            $message = "NID must contain only digits!";
        } else {
            $message = "NID must be exactly 12 digits!";
        }
    }
    // Validate password (8+ chars, upper, lower, number)
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/', $password)) {
        $message = "Password must be at least 8 characters, with uppercase, lowercase, and numbers!";
    }
    // Validate email
    elseif (!$email) {
        $message = "Invalid email address!";
    }
    else {
        try {
            if ($auth->register($username, $password, $phone, $nid, $email)) {
                header("Location: login.php");
                exit;
            } else {
                $message = "Registration failed! Username, phone, email, or NID may already be in use.";
            }
        } catch (Exception $e) {
            $message = "Registration error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Mobile Wallet</a>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Register</h2>
                        <?php if ($message): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}" title="Password must be at least 8 characters, with uppercase, lowercase, and numbers">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required pattern="\d{11}" title="Phone number must be exactly 11 digits">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="nid" class="form-label">NID Number</label>
                                <input type="text" class="form-control" id="nid" name="nid" required pattern="\d{12}" title="NID must be exactly 12 digits">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>
                        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
?>