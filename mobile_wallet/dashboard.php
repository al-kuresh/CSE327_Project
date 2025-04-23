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
$message = isset($_GET['success']) ? $_GET['success'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Current Balance</h5>
                <p class="card-text">৳<?php echo number_format($balance, 2); ?></p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Send Money</h5>
                        <a href="send_money.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cash In</h5>
                        <a href="cash_in.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cash Out</h5>
                        <a href="cash_out.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Check Balance</h5>
                        <a href="check_balance.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mobile Recharge</h5>
                        <a href="mobile_recharge.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pay Bill</h5>
                        <a href="pay_bill.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Transaction History</h5>
                        <a href="transaction_history.php" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>