<?php
interface BillPayment {
    public function payBill(string $accountNumber, float $amount): string;
}

class LegacyBillSystem {
    public function makePayment($account, $amt) {
        return "Legacy payment processed for account $account: ৳$amt";
    }
}

class BillAdapter implements BillPayment {
    private $legacySystem;

    // Adapter Pattern: BillAdapter adapts LegacyBillSystem to BillPayment interface
    public function __construct(LegacyBillSystem $legacySystem) {
        $this->legacySystem = $legacySystem;
    }

    public function payBill(string $accountNumber, float $amount): string {
        return $this->legacySystem->makePayment($accountNumber, $amount);
    }
}
?>