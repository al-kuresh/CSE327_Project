<?php
// Builder Pattern: User object creation
class User {
    public $username;
    public $password;
    public $phone;
    public $nid;
    public $email;
}

class UserBuilder {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function setUsername(string $username): self {
        $this->user->username = $username;
        return $this;
    }

    public function setPassword(string $password): self {
        $this->user->password = $password;
        return $this;
    }

    public function setPhone(string $phone): self {
        $this->user->phone = $phone;
        return $this;
    }

    public function setNid(string $nid): self {
        $this->user->nid = $nid;
        return $this;
    }

    public function setEmail(string $email): self {
        $this->user->email = $email;
        return $this;
    }

    public function build(): User {
        return $this->user;
    }
}
?>