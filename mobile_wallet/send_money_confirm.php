<?php
require_once 'php/auth.php';
require_once 'php/transactions.php';
$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$transactionManager = new TransactionManager();
$message = '';

if (!isset($_SESSION['send_money'])) {
    header("Location: send_money.php");
    exit;
}

$data = $_SESSION['send_money'];
$recipient = $data['recipient'];
$amount = $data['amount'];
$payment_method = $data['payment_method'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if ($auth->verifyPassword($_SESSION['user_id'], $password)) {
            try {
                $result = $transactionManager->processTransaction(
                    $_SESSION['user_id'],
                    'send_money',
                    $amount,
                    $recipient,
                    $payment_method
                );

                if ($result['success']) {
                    $_SESSION['balance'] = $transactionManager->getBalance($_SESSION['user_id']);
                    $message = "Successfully sent ৳$amount to $recipient! " . $result['payment'];
                    unset($_SESSION['send_money']);
                    header("Refresh:3;url=dashboard.php");
                } else {
                    $message = "Error: " . $result['error'];
                }
            } catch (Exception $e) {
                $message = "Transaction failed: " . $e->getMessage();
            }
        } else {
            $message = "Incorrect password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Send Money</title>
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
        <h2>Confirm Send Money</h2>
        <div class="card">
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert <?php echo strpos($message, 'Error') === false && strpos($message, 'Incorrect') === false ? 'alert-success' : 'alert-danger'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <h5>Transaction Details</h5>
                <p><strong>Recipient:</strong> <?php echo htmlspecialchars($recipient); ?></p>
                <p><strong>Amount:</strong> ৳<?php echo number_format($amount, 2); ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
                <form method="POST">
                    <div class="mb-3">
                        <label for="password" class="form-label">Enter Password to Confirm</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirm Transaction</button>
                    <a href="send_money.php" class="btn btn-secondary">Cancel</a>
                </form>
                <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>