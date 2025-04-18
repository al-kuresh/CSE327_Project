<?php
class User {
    public $username;
    public $phone;
    public $nid;
    public $balance;

    public function __construct($username, $phone, $nid, $balance) {
        $this->username = $username;
        $this->phone = $phone;
        $this->nid = $nid;
        $this->balance = $balance;
    }
}

class UserBuilder {
    private $username;
    private $phone;
    private $nid;
    private $balance = 0.00;

    // Builder Pattern: UserBuilder constructs User object step-by-step
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
        return $this;
    }

    public function setNid($nid) {
        $this->nid = $nid;
        return $this;
    }

    public function setBalance($balance) {
        $this->balance = $balance;
        return $this;
    }

    public function build() {
        return new User($this->username, $this->phone, $this->nid, $this->balance);
    }
}
?>