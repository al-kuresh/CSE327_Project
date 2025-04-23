<?php
require_once 'bill_adapter.php';

// Strategy Pattern: Bill payment strategies
interface BillPaymentStrategy {
    public function payBill(float $amount, array $details): string;
}

class ElectricityBillPayment implements BillPaymentStrategy {
    public function payBill(float $amount, array $details): string {
        return "Processed electricity bill payment of ৳$amount to {$details['provider']} (Account: {$details['account_number']})";
    }
}

class WifiBillPayment implements BillPaymentStrategy {
    public function payBill(float $amount, array $details): string {
        return "Processed WiFi bill payment of ৳$amount to {$details['provider']} (Account: {$details['account_number']})";
    }
}

class ShoppingBillPayment implements BillPaymentStrategy {
    public function payBill(float $amount, array $details): string {
        return "Processed shopping bill payment of ৳$amount to {$details['provider']} (Merchant: {$details['account_number']})";
    }
}

class BillManager {
    private $strategy;

    public function setStrategy(BillPaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function processBillPayment(float $amount, array $details): string {
        return $this->strategy->payBill($amount, $details);
    }
}
?>