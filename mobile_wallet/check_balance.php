<?php
require_once 'php/auth.php';
require_once 'php/transactions.php';
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$transactionManager = new TransactionManager();
$balance = $transactionManager->getBalance($_SESSION['user_id']);
$_SESSION['balance'] = $balance; // Ensure session balance is updated
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Balance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'send_money.php' ? 'active' : ''; ?>" href="send_money.php">Send Money</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cash_in.php' ? 'active' : ''; ?>" href="cash_in.php">Cash In</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cash_out.php' ? 'active' : ''; ?>" href="cash_out.php">Cash Out</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'check_balance.php' ? 'active' : ''; ?>" href="check_balance.php">Check Balance</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'mobile_recharge.php' ? 'active' : ''; ?>" href="mobile_recharge.php">Mobile Recharge</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'pay_bill.php' ? 'active' : ''; ?>" href="pay_bill.php">Pay Bill</a>
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'transaction_history.php' ? 'active' : ''; ?>" href="transaction_history.php">Transaction History</a>
                    <a class="nav-link" href="php/auth.php?logout=1">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Check Balance</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Current Balance</h5>
                <p class="card-text">৳<?php echo number_format($balance, 2); ?></p>
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>