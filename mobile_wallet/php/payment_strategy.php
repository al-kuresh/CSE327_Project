<?php
interface PaymentStrategy {
    public function pay(float $amount): string;
}

class WalletPayment implements PaymentStrategy {
    // Strategy Pattern: WalletPayment implements payment logic
    public function pay(float $amount): string {
        return "Payment processed via wallet: ৳$amount";
    }
}

class BankPayment implements PaymentStrategy {
    // Strategy Pattern: BankPayment implements payment logic
    public function pay(float $amount): string {
        return "Payment processed via bank: ৳$amount";
    }
}

class PaymentContext {
    private PaymentStrategy $strategy;

    // Strategy Pattern: PaymentContext allows dynamic strategy selection
    public function __construct(PaymentStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function setStrategy(PaymentStrategy $strategy): void {
        $this->strategy = $strategy;
    }

    public function pay(float $amount): string {
        return $this->strategy->pay($amount);
    }
}
?>