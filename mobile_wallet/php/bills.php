<?php
require_once 'db_singleton.php';
require_once 'bill_adapter.php';

class BillManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function payBill($user_id, $type, $provider, $account_number, $amount) {
        // Adapter Pattern: Use BillAdapter to interface with LegacyBillSystem
        $legacySystem = new LegacyBillSystem();
        $adapter = new BillAdapter($legacySystem);
        $result = $adapter->payBill($account_number, $amount);

        $stmt = $this->db->prepare("INSERT INTO bills (user_id, type, provider, account_number, amount, status) VALUES (?, ?, ?, ?, ?, 'paid')");
        $stmt->bind_param("isssd", $user_id, $type, $provider, $account_number, $amount);
        $stmt->execute();

        return $result;
    }
}
?>