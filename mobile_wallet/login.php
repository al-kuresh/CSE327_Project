<?php
require_once 'php/auth.php';
$auth = new Auth();

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($auth->login($phone, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid phone number or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mobile Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Header with Mobile Wallet branding -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Mobile Wallet</a>
        </div>
    </nav>

    <div class="container mt-3">
        <h2 class="fs-4">Login</h2>
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body p-3">
                        <?php if ($message): ?>
                            <div class="alert alert-danger alert-sm"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-2">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control form-control-sm" id="phone" name="phone" placeholder=" " required>
                            </div>
                            <div class="mb-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Login</button>
                            <a href="register.php" class="btn btn-link btn-sm">Register</a>
                            <a href="reset_password.php" class="btn btn-link btn-sm">Forgot Password?</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
?>