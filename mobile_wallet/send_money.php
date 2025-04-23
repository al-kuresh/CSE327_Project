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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipient = $_POST['recipient'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $payment_method = $_POST['payment_method'] ?? '';

    if ($amount > 0 && preg_match('/^\d{11}$/', $recipient)) {
        $_SESSION['send_money'] = [
            'recipient' => $recipient,
            'amount' => $amount,
            'payment_method' => $payment_method
        ];
        header("Location: send_money_confirm.php");
        exit;
    } else {
        $message = "Please enter a valid phone number (11 digits) and amount!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Money</title>
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
        <h2>Send Money</h2>
        <div class="card">
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Recipient Phone Number</label>
                        <input type="text" class="form-control" id="recipient" name="recipient" required pattern="\d{11}" title="Phone number must be exactly 11 digits">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (৳)</label>
                        <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-control" id="payment_method" name="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Proceed</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>