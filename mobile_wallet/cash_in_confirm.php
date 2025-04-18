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

if (!isset($_SESSION['cash_in'])) {
    header("Location: cash_in.php");
    exit;
}

$data = $_SESSION['cash_in'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
        if ($data['amount'] > 0) {
            $result = $transactionManager->processTransaction($_SESSION['user_id'], 'cash_in', $data['amount'], $data['merchant_number']);
            if ($result['success']) {
                // Update session balance
                $_SESSION['balance'] = $transactionManager->getBalance($_SESSION['user_id']);
                unset($_SESSION['cash_in']);
                $message = "Cash in successful! " . $result['payment'];
                header("Location: dashboard.php?success=" . urlencode($message));
                exit;
            } else {
                $message = $result['error'];
            }
        } else {
            $message = "Invalid amount!";
        }
    } else {
        unset($_SESSION['cash_in']);
        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Cash In</title>
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
        <h2>Confirm Cash In</h2>
        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <p><strong>Amount:</strong> ৳<?php echo number_format($data['amount'], 2); ?></p>
                    <p><strong>Merchant Number:</strong> <?php echo htmlspecialchars($data['merchant_number']); ?></p>
                    <form method="POST">
                        <input type="hidden" name="confirm" value="yes">
                        <button type="submit" class="btn btn-primary">Confirm</button>
                        <a href="cash_in.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>