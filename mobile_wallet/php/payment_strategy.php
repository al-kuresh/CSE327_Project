<?php
// Strategy Pattern: Payment processing strategies
interface PaymentStrategy {
    public function processPayment(float $amount): string;
}

class CreditCardPayment implements PaymentStrategy {
    public function processPayment(float $amount): string {
        return "Processed credit card payment of ৳$amount";
    }
}

class BankTransferPayment implements PaymentStrategy {
    public function processPayment(float $amount): string {
        return "Processed bank transfer payment of ৳$amount";
    }
}

class PaymentProcessor {
    private $strategy;

    public function setStrategy(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function process(float $amount): string {
        return $this->strategy->processPayment($amount);
    }
}
?>