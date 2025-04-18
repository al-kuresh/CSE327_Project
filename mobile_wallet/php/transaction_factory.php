<?php
class Transaction {
    private string $type;
    private float $amount;
    private ?string $recipient;

    public function __construct(string $type, float $amount, ?string $recipient) {
        $this->type = $type;
        $this->amount = $amount;
        $this->recipient = $recipient;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getAmount(): float {
        return $this->amount;
    }

    public function getRecipient(): ?string {
        return $this->recipient;
    }
}

class TransactionFactory {
    // Factory Pattern: TransactionFactory creates Transaction objects
    public static function createTransaction(string $type, float $amount, ?string $recipient = null): Transaction {
        return new Transaction($type, $amount, $recipient);
    }
}
?>