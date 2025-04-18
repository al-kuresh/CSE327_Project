<?php
interface TransactionObserver {
    public function update(object $transaction): string;
}

class EmailNotification implements TransactionObserver {
    // Observer Pattern: EmailNotification updates on transaction events
    public function update(object $transaction): string {
        return "Email sent for {$transaction->getType()} of {$transaction->getAmount()}";
    }
}

class SMSNotification implements TransactionObserver {
    // Observer Pattern: SMSNotification updates on transaction events
    public function update(object $transaction): string {
        return "SMS sent for {$transaction->getType()} of {$transaction->getAmount()}";
    }
}

class TransactionSubject {
    /** @var TransactionObserver[] */
    private array $observers = [];

    // Observer Pattern: TransactionSubject manages observers
    public function attach(TransactionObserver $observer): void {
        $this->observers[] = $observer;
    }

    /**
     * @return string[]
     */
    public function notify(object $transaction): array {
        $messages = [];
        foreach ($this->observers as $observer) {
            $messages[] = $observer->update($transaction);
        }
        return $messages;
    }
}
?>