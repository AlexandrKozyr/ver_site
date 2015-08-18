<?php

namespace Customers\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class CustomersTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }
    /**
     * this method save one customer to Db or change it if customer with id_1c 
     * already exist
     * @param \Customers\Model\Customer $customer
     */
    public function saveCustomer(Customer $customer) {
        $dataToSave = array(
            'login'     => $customer->login,
            'pass'      => $customer->pass,
            'name'      => $customer->name,
            'email'     => $customer->email,
            'is_active' => $customer->is_active,
            'id_1c'     => $customer->id_1c,
        );
        if ($this->getUserById1c($customer->id_1c)) {
            $this->tableGateway->update($dataToSave, array('id_1c' => $id_1c));
        }else{
            $this->tableGateway->insert($dataToSave);
        }
    }
    /**
     * 
     * @param int $id
     * @return array
     */
    public function getUserById1c($id) {
        $id_1c    = (int) $id;
        $customer = $this->tableGateway->select(array('id_1c' => $id_1c));
        $row      = $customer->current();
        if ($row) {
            return $row;
        } else {
            return false;
        }
    }
    public function getUserByLogin($login) {
        
        $customer = $this->tableGateway->select(array('login' => $login));
        $row      = $customer->current();
        if ($row) {
            return $row;
        } else {
            throw new \Exception("Could not find row $id");
        }
    }

}
