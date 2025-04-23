<?php
require_once 'vendor/autoload.php';
require_once 'db_singleton.php';
use PHPMailer\PHPMailer\PHPMailer;
require_once 'config.php';

interface TransactionObserver {
    public function notify(int $user_id, Transaction $transaction): string;
}

class EmailNotification implements TransactionObserver {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function notify(int $user_id, Transaction $transaction): string {
        $stmt = $this->db->prepare("SELECT username, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return "Email notification failed: User not found.";
        }

        $username = $user['username'];
        $email = $user['email'];
        $type = $transaction->getType();
        $amount = $transaction->getAmount();

        // Customize message based on transaction type
        $messageBody = "Dear $username,<br>Your transaction of ৳$amount was successful.";
        if ($type === 'receive_money') {
            $messageBody = "Dear $username,<br>You have received ৳$amount from another user.";
        } elseif ($type === 'send_money') {
            $messageBody = "Dear $username,<br>You have sent ৳$amount to another user.";
        }

        $mailer = new PHPMailer(true);
        try {
            $mailer->isSMTP();
            $mailer->Host = SMTP_HOST;
            $mailer->SMTPAuth = true;
            $mailer->Username = SMTP_USERNAME;
            $mailer->Password = SMTP_PASSWORD;
            $mailer->SMTPSecure = SMTP_SECURE;
            $mailer->Port = SMTP_PORT;
            $mailer->setFrom('no-reply@mobilewallet.com', 'Mobile Wallet');
            $mailer->addAddress($email, $username);
            $mailer->isHTML(true);
            $mailer->Subject = 'Transaction Notification';
            $mailer->Body = $messageBody;
            $mailer->send();
            return "Email notification sent to $email.";
        } catch (Exception $e) {
            return "Email notification failed: {$mailer->ErrorInfo}";
        }
    }
}

class SMSNotification implements TransactionObserver {
    public function notify(int $user_id, Transaction $transaction): string {
        // Placeholder for SMS notification logic (e.g., using Twilio API)
        $type = $transaction->getType();
        $amount = $transaction->getAmount();
        return "SMS notification for $type transaction of ৳$amount would be sent here.";
    }
}
?>