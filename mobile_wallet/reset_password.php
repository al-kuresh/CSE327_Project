<?php
require_once 'php/auth.php';
$auth = new Auth();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nid = $_POST['nid'];
    $new_password = $_POST['new_password'];
    if ($auth->resetPassword($nid, $new_password)) {
        $message = "Password reset successful! Please <a href='login.php'>login</a> with your new password.";
    } else {
        $message = "Invalid NID number!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <h2 class="card-title text-center">Reset Password</h2>
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo $message; ?></div>
                        <?php else: ?>
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="nid" class="form-label">NID Number</label>
                                    <input type="text" class="form-control" id="nid" name="nid" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                            </form>
                            <p class="mt-3 text-center"><a href="login.php">Back to Login</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>