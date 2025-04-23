<?php
require_once 'php/auth.php';
require_once 'php/db_singleton.php';

$auth = new Auth();
$db = Database::getInstance()->getConnection();

// Redirect to login if not logged in
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate required POST data
    if (!isset($_POST['phone']) || empty(trim($_POST['phone']))) {
        $message = "Phone number is required.";
    } elseif (!preg_match("/^\d{11}$/", trim($_POST['phone']))) {
        $message = "Phone number must be exactly 11 digits.";
    } elseif (!isset($_POST['operator']) || empty(trim($_POST['operator']))) {
        $message = "Operator is required.";
    } elseif (!isset($_POST['amount']) || floatval($_POST['amount']) <= 0) {
        $message = "Amount must be greater than 0.";
    } else {
        $phone = trim($_POST['phone']);
        $operator = trim($_POST['operator']);
        $amount = floatval($_POST['amount']);
        $user_id = $_SESSION['user_id'];

        // Check user balance
        $stmt = $db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($user = $result->fetch_assoc()) {
            $balance = $user['balance'];
            if ($balance < $amount) {
                $message = "Insufficient balance to perform this recharge.";
            } else {
                // Deduct amount from user balance
                $new_balance = $balance - $amount;
                $stmt = $db->prepare("UPDATE users SET balance = ? WHERE id = ?");
                $stmt->bind_param("di", $new_balance, $user_id);
                if ($stmt->execute()) {
                    // Log the transaction
                    $stmt = $db->prepare("INSERT INTO transactions (user_id, amount, type, description) VALUES (?, ?, ?, ?)");
                    if (!$stmt) {
                        error_log("Prepare failed for transaction insert: " . $db->error);
                        $message = "Failed to log transaction: " . $db->error;
                    } else {
                        $type = 'mobile_recharge';
                        $description = "Mobile Recharge: $phone ($operator)";
                        $stmt->bind_param("idss", $user_id, $amount, $type, $description);
                        if ($stmt->execute()) {
                            $message = "Recharge successful!";
                            $_SESSION['balance'] = $new_balance; // Update session balance
                        } else {
                            error_log("Transaction insert failed: " . $stmt->error);
                            $message = "Failed to log transaction: " . $stmt->error;
                        }
                    }
                } else {
                    $message = "Failed to perform recharge: " . $db->error;
                }
            }
        } else {
            $message = "User not found.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Recharge Confirmation - Mobile Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Mobile Wallet</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="php/auth.php?logout=1">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <h2 class="fs-4">Mobile Recharge Confirmation</h2>
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body p-3">
                        <?php if ($message): ?>
                            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>
                        <a href="mobile_recharge.php" class="btn btn-primary btn-sm">Recharge Again</a>
                        <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
?>