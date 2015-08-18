<?php

namespace Customers\Model;

class Customer {

    public $id;
    public $login;
    public $pass;
    public $name;
    public $email;
    public $is_active;
    public $id_1c;

    public function exchangeArray($data) {
        $this->login     = (isset($data['login'])) ? $data['login'] : null;
        $this->pass      = (isset($data['pass'])) ? $data['pass'] : null;
        $this->name      = (isset($data['name'])) ? $data['name'] : null;
        $this->email     = (isset($data['email'])) ? $data['email'] : null;
        $this->is_active = (isset($data['is_active'])) ? $data['is_active'] : null;
        $this->id_1c     = (isset($data['id_1c'])) ? $data['id_1c'] : null;
    }

}
