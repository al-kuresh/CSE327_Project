<?php
require_once 'php/auth.php';
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

session_start();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['mobile_recharge'] = [
        'phone' => $_POST['phone'],
        'amount' => floatval($_POST['amount'])
    ];
    header("Location: mobile_recharge_confirm.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Recharge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Mobile Recharge</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (৳)</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Next</button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>