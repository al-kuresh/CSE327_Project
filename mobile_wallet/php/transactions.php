<?php
require_once 'db_singleton.php';
require_once 'transaction_factory.php';
require_once 'payment_strategy.php';
require_once 'transaction_observer.php';
require_once 'bills.php';
require_once 'bill_adapter.php';

class TransactionManager {
    private $db;
    private $observers = [];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        // Observer Pattern: Attach observers
        $this->observers[] = new EmailNotification();
        $this->observers[] = new SMSNotification();
    }

    public function getBalance(int $user_id): float {
        $stmt = $this->db->prepare("SELECT balance FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user['balance'] ?? 0.0;
    }

    public function processTransaction(int $user_id, string $type, float $amount, $details, ?string $payment_method = null): array {
        try {
            $this->db->begin_transaction();

            // Validate merchant for cash_in and cash_out
            if (in_array($type, ['cash_in', 'cash_out'])) {
                $merchant_number = $details;
                $stmt = $this->db->prepare("SELECT id FROM merchants WHERE merchant_number = ?");
                $stmt->bind_param("s", $merchant_number);
                $stmt->execute();
                if ($stmt->get_result()->num_rows == 0) {
                    throw new Exception("Invalid merchant number!");
                }
            }

            // Create transaction object (Factory Pattern)
            $details_array = is_array($details) ? $details : ['recipient' => $details, 'merchant_number' => $details, 'phone' => $details];
            $transaction = TransactionFactory::createTransaction($type, $amount, $details_array);

            // Process payment (Strategy Pattern)
            $payment_message = '';
            if ($payment_method) {
                $payment_processor = new PaymentProcessor();
                if ($payment_method == 'credit_card') {
                    $payment_processor->setStrategy(new CreditCardPayment());
                } elseif ($payment_method == 'bank_transfer') {
                    $payment_processor->setStrategy(new BankTransferPayment());
                }
                $payment_message = $payment_processor->process($amount);
            }

            // Update balances
            if ($type == 'cash_in') {
                $stmt = $this->db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
                $stmt->bind_param("di", $amount, $user_id);
                $stmt->execute();
            } elseif (in_array($type, ['send_money', 'cash_out', 'mobile_recharge', 'pay_bill'])) {
                $stmt = $this->db->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
                $stmt->bind_param("di", $amount, $user_id);
                $stmt->execute();
                if ($stmt->affected_rows == 0) {
                    throw new Exception("Insufficient balance!");
                }

                // For send_money, credit recipient
                if ($type == 'send_money') {
                    $recipient_phone = $details;
                    $stmt = $this->db->prepare("SELECT id, phone FROM users WHERE phone = ?");
                    $stmt->bind_param("s", $recipient_phone);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        throw new Exception("Recipient not found!");
                    }
                    $recipient = $result->fetch_assoc();
                    $recipient_id = $recipient['id'];
                    $sender_phone = $recipient['phone']; // Use recipient's phone for sender_phone

                    $stmt = $this->db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
                    $stmt->bind_param("di", $amount, $recipient_id);
                    $stmt->execute();

                    // Record receive_money transaction
                    $receive_transaction = TransactionFactory::createTransaction('receive_money', $amount, ['recipient' => $sender_phone]);
                    $stmt = $this->db->prepare("INSERT INTO transactions (user_id, type, amount, sender_phone, created_at) VALUES (?, 'receive_money', ?, ?, NOW())");
                    $stmt->bind_param("ids", $recipient_id, $amount, $sender_phone);
                    $stmt->execute();

                    // Notify recipient (Observer Pattern)
                    $notifications = [];
                    foreach ($this->observers as $observer) {
                        $notifications[] = $observer->notify($recipient_id, $receive_transaction);
                    }
                }
            }

            // Process bill payment (Strategy and Adapter Patterns)
            if ($type == 'pay_bill') {
                $bill_manager = new BillManager();
                $bill_details = $details_array;
                if ($bill_details['bill_type'] == 'electricity') {
                    $bill_manager->setStrategy(new ElectricityBillPayment());
                } elseif ($bill_details['bill_type'] == 'wifi') {
                    $bill_manager->setStrategy(new WifiBillPayment());
                } elseif ($bill_details['bill_type'] == 'shopping') {
                    $bill_manager->setStrategy(new ShoppingBillPayment());
                }
                $payment_message = $bill_manager->processBillPayment($amount, $bill_details);

                // Adapter for legacy system
                $legacy_system = new LegacyBillSystem();
                $adapter = new BillSystemAdapter($legacy_system);
                $payment_message .= "; " . $adapter->makePayment($amount, $bill_details['account_number']);
            }

            // Record transaction
            $stmt = $this->db->prepare("INSERT INTO transactions (user_id, type, amount, recipient_phone, merchant_number, phone, bill_type, provider, account_number, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $recipient_phone = $type == 'send_money' ? $details_array['recipient'] : null;
            $merchant_number = in_array($type, ['cash_in', 'cash_out']) ? $details_array['merchant_number'] : null;
            $phone = $type == 'mobile_recharge' ? $details_array['phone'] : null;
            $bill_type = $type == 'pay_bill' ? $details_array['bill_type'] : null;
            $provider = $type == 'pay_bill' ? $details_array['provider'] : null;
            $account_number = $type == 'pay_bill' ? $details_array['account_number'] : null;
            $stmt->bind_param("isdsdssss", $user_id, $type, $amount, $recipient_phone, $merchant_number, $phone, $bill_type, $provider, $account_number);
            $stmt->execute();

            // Notify user (Observer Pattern)
            $notifications = [];
            foreach ($this->observers as $observer) {
                $notifications[] = $observer->notify($user_id, $transaction);
            }

            $this->db->commit();
            return ['success' => true, 'payment' => $payment_message, 'notifications' => $notifications];
        } catch (Exception $e) {
            $this->db->rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getTransactionHistory(int $user_id): array {
        $stmt = $this->db->prepare("
            SELECT t.*, u.phone AS sender_phone
            FROM transactions t
            LEFT JOIN users u ON t.user_id = u.id
            WHERE t.user_id = ?
            ORDER BY t.created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>