<?php
require_once 'php/auth.php';
$auth = new Auth();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $nid = $_POST['nid'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Server-side validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (!preg_match("/^\d{12}$/", $nid)) {
        $error = "NID must be exactly 12 digits.";
    } elseif (strlen($new_password) < 8 || !preg_match("/[A-Z]/", $new_password) || !preg_match("/[a-z]/", $new_password) || !preg_match("/[0-9]/", $new_password)) {
        $error = "Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.";
    } else {
        try {
            if ($auth->resetPassword($email, $nid, $new_password)) {
                $message = "Password reset successful! <a href=\"login.php\">Login</a>";
            } else {
                $error = "Failed to reset password. Please check your email and NID.";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Reset Password</h2>
        <div class="card">
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="nid" class="form-label">NID Number</label>
                        <input type="text" class="form-control" id="nid" name="nid" required pattern="\d{12}" title="NID must be exactly 12 digits">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required 
                               pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}" 
                               title="Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number">
                    </div>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                    <a href="login.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>