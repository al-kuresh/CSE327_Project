<?php
// Adapter Pattern: Adapts external bill payment systems
interface ExternalBillPaymentSystem {
    public function makePayment(float $amount, string $account): string;
}

class LegacyBillSystem {
    public function processPayment(float $amount, string $account): string {
        return "Legacy system processed payment of ৳$amount for account $account";
    }
}

class BillSystemAdapter implements ExternalBillPaymentSystem {
    private $legacySystem;

    public function __construct(LegacyBillSystem $legacySystem) {
        $this->legacySystem = $legacySystem;
    }

    public function makePayment(float $amount, string $account): string {
        return $this->legacySystem->processPayment($amount, $account);
    }
}
?>