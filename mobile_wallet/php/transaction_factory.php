<?php
class Transaction {
    private $type;
    private $amount;
    private $details;

    public function __construct(string $type, float $amount, array $details) {
        $this->type = $type;
        $this->amount = $amount;
        $this->details = $details;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function getDetails(): array {
        return $this->details;
    }
}

class TransactionFactory {
    public static function createTransaction(string $type, float $amount, array $details): Transaction {
        $validTypes = [
            'send_money',
            'receive_money', // Added to support recipient transactions
            'cash_in',
            'cash_out',
            'mobile_recharge',
            'pay_bill'
        ];

        if (!in_array($type, $validTypes)) {
            throw new Exception("Unknown transaction type: $type");
        }

        return new Transaction($type, $amount, $details);
    }
}
?>