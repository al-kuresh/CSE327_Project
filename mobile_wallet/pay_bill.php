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
    <title>Pay Bill - Mobile Wallet</title>
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
        <h2 class="fs-4">Pay a Bill</h2>
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="POST" action="pay_bill_confirm.php">
                            <div class="mb-2">
                                <label for="bill_type" class="form-label">Bill Type</label>
                                <select class="form-control form-control-sm" id="bill_type" name="bill_type" required>
                                    <option value="">Select a bill type</option>
                                    <option value="electricity">Electricity</option>
                                    <option value="water">Water</option>
                                    <option value="gas">Gas</option>
                                    <option value="internet">Internet</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="amount" name="amount" placeholder="e.g., 1000.00" required>
                            </div>
                            <div class="mb-2">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-control form-control-sm" id="payment_method" name="payment_method" required>
                                    <option value="">Select a payment method</option>
                                    <option value="wallet">Wallet Balance</option>
                                    <option value="credit_card">Credit Card</option>
                                    <option value="debit_card">Debit Card</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Pay Bill</button>
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