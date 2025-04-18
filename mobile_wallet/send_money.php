<?php
require_once 'php/auth.php';
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['send_money'] = [
        'recipient' => $_POST['recipient'],
        'amount' => floatval($_POST['amount']),
        'payment_method' => $_POST['payment_method']
    ];
    header("Location: send_money_confirm.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Mobile Wallet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                    <a class="nav-link" href="send_money.php">Send Money</a>
                    <a class="nav-link" href="cash_in.php">Cash In</a>
                    <a class="nav-link" href="cash_out.php">Cash Out</a>
                    <a class="nav-link" href="check_balance.php">Check Balance</a>
                    <a class="nav-link" href="mobile_recharge.php">Mobile Recharge</a>
                    <a class="nav-link" href="pay_bill.php">Pay Bill</a>
                    <a class="nav-link" href="transaction_history.php">Transaction History</a>
                    <a class="nav-link" href="php/auth.php?logout=1">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Send Money</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="recipient" class="form-label">Recipient Phone</label>
                <input type="text" class="form-control" id="recipient" name="recipient" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount (৳)</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-control" id="payment_method" name="payment_method">
                    <option value="wallet">Wallet</option>
                    <option value="bank">Bank</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Next</button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>