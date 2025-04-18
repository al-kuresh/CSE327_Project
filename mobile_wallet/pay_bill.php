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
    $_SESSION['pay_bill'] = [
        'type' => $_POST['type'],
        'provider' => $_POST['provider'],
        'account_number' => $_POST['account_number'],
        'amount' => floatval($_POST['amount'])
    ];
    header("Location: pay_bill_confirm.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Pay Bill</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="type" class="form-label">Bill Type</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="electricity">Electricity</option>
                    <option value="wifi">WiFi</option>
                    <option value="shopping">Shopping</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="provider" class="form-label">Provider/Service Name</label>
                <input type="text" class="form-control" id="provider" name="provider" required>
            </div>
            <div class="mb-3">
                <label for="account_number" class="form-label">Account/Meter/Merchant Number</label>
                <input type="text" class="form-control" id="account_number" name="account_number" required>
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