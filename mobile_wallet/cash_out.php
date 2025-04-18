<?php
require_once 'php/auth.php';
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $merchant_number = filter_input(INPUT_POST, 'merchant_number', FILTER_SANITIZE_STRING);

    if ($amount === false || $amount <= 0) {
        $errors[] = 'Please enter a valid amount.';
    }
    if (empty($merchant_number)) {
        $errors[] = 'Please enter a merchant number.';
    }

    if (empty($errors)) {
        $_SESSION['cash_out'] = [
            'amount' => $amount,
            'merchant_number' => $merchant_number
        ];
        header("Location: cash_out_confirm.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Out</title>
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
        <h2>Cash Out</h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <form method="post" action="cash_out.php">
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="merchant_number" class="form-label">Merchant Number</label>
                <input type="text" class="form-control" id="merchant_number" name="merchant_number" required>
            </div>
            <button type="submit" class="btn btn-primary">Next</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>