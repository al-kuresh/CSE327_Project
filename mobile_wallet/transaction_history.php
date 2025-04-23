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
        <h2>Transaction History</h2>
        <div class="card">
            <div class="card-body">
                <?php if (empty($transactions)): ?>
                    <p>No transactions found.</p>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                                    <td>৳<?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td>
                                        <?php
                                        if ($transaction['type'] == 'send_money') {
                                            echo "To: " . htmlspecialchars($transaction['recipient_phone']);
                                        } elseif ($transaction['type'] == 'receive_money') {
                                            echo "From: " . htmlspecialchars($transaction['sender_phone'] ?? 'Unknown');
                                        } elseif ($transaction['type'] == 'cash_in' || $transaction['type'] == 'cash_out') {
                                            echo "Merchant: " . htmlspecialchars($transaction['merchant_number']);
                                        } elseif ($transaction['type'] == 'mobile_recharge') {
                                            echo "Phone: " . htmlspecialchars($transaction['phone']);
                                        } elseif ($transaction['type'] == 'pay_bill') {
                                            echo htmlspecialchars($transaction['bill_type']) . " - " . htmlspecialchars($transaction['provider']);
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>