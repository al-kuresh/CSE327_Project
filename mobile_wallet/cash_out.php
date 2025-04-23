<?php
require_once 'php/auth.php';

$auth = new Auth();

// Redirect to login if not logged in
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Out - Mobile Wallet</title>
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
        <h2 class="fs-4">Cash Out</h2>
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="POST" action="cash_out_confirm.php">
                            <div class="mb-2">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="amount" name="amount" placeholder="e.g., 1000.00" required>
                            </div>
                            <div class="mb-2">
                                <label for="withdrawal_method" class="form-label">Withdrawal Method</label>
                                <select class="form-control form-control-sm" id="withdrawal_method" name="withdrawal_method" required>
                                    <option value="">Select a withdrawal method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="mobile_banking">Mobile Banking</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="account_details" class="form-label">Account Details</label>
                                <input type="text" class="form-control form-control-sm" id="account_details" name="account_details" placeholder="e.g., Bank Account Number or Mobile Number" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Cash Out</button>
                            <a href="dashboard.php" class="btn btn-secondary btn-sm">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
?>