<?php
require_once 'db_singleton.php';
require_once 'transaction_factory.php';
require_once 'payment_strategy.php';
require_once 'transaction_observer.php';

class TransactionManager {
    private $db;
    private $observers;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        // Observer Pattern: Attach observers for transaction notifications
        $this->observers = new TransactionSubject();
        $this->observers->attach(new EmailNotification());
        $this->observers->attach(new SMSNotification());
    }

    public function processTransaction($user_id, $type, $amount, $recipient = null, $payment_method = 'wallet') {
        $payment_strategy = $payment_method === 'bank' ? new BankPayment() : new WalletPayment();
        // Strategy Pattern: Use PaymentContext to select payment strategy dynamically
        $context = new PaymentContext($payment_strategy);
        $payment_message = $context->pay($amount);

        // Factory Pattern: Use TransactionFactory to create Transaction object
        $transaction = TransactionFactory::createTransaction($type, $amount, $recipient);

        $description = '';
        $recipient_id = null;

        if ($type === 'send_money') {
            $stmt = $this->db->prepare("SELECT id, username FROM users WHERE phone = ?");
            $stmt->bind_param("s", $recipient);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                return ['success' => false, 'error' => 'Recipient not found'];
            }
            $recipient_user = $result->fetch_assoc();
            $recipient_id = $recipient_user['id'];
            $description = "Sent to {$recipient_user['username']} ({$recipient})";

            $stmt = $this->db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->bind_param("di", $amount, $recipient_id);
            $stmt->execute();
        } elseif ($type === 'cash_in') {
            $description = "Cash in from merchant {$recipient}";
        } elseif ($type === 'cash_out') {
            $description = "Cash out to merchant {$recipient}";
        } elseif ($type === 'mobile_recharge') {
            $description = "Recharge for {$recipient}";
        } elseif ($type === 'pay_bill') {
            $description = "Bill payment to {$recipient}";
        }

        $stmt = $this->db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->bind_param("di", $amount, $user_id);
        if ($type === 'cash_in') {
            $stmt = $this->db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->bind_param("di", $amount, $user_id);
        }
        if (!$stmt->execute()) {
            return ['success' => false, 'error' => 'Balance update failed'];
        }

        $stmt = $this->db->prepare("INSERT INTO transactions (user_id, type, amount, recipient_id, description, recipient) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdiss", $user_id, $type, $amount, $recipient_id, $description, $recipient);
        if (!$stmt->execute()) {
            return ['success' => false, 'error' => 'Transaction recording failed'];
        }

        $notifications = $this->observers->notify($transaction);
        return [
            'success' => true,
            'payment' => $payment_message,
            'notifications' => $notifications
        ];
    }

    public function getTransactionHistory($user_id) {
        $stmt = $this->db->prepare("SELECT type, amount, recipient, description, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $transactions = [];
        while ($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
        return $transactions;
    }

    public function getBalance($user_id) {
        $stmt = $this->db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['balance'];
    }
}
?>