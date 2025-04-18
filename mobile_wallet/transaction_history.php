<?php
require_once 'php/auth.php';
require_once 'php/transactions.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$transactionManager = new TransactionManager();
$transactions = $transactionManager->getTransactionHistory($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
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
        <h2>Transaction History</h2>
        <?php if (empty($transactions)): ?>
            <div class="alert alert-info">No transactions found.</div>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Recipient</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($transaction['created_at']))); ?></td>
                            <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                            <td>৳<?php echo number_format($transaction['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($transaction['recipient'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>