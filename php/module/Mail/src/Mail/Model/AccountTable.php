<?php
/**
 * Wrapper for Entity Account
 * User: etienne_odiwi
 */
namespace Mail\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class AccountTable
{
    protected $dbAdapter;
    protected $tableGateway;

    public function __construct(Adapter $dbAdapter, TableGateway $tableGateway)
    {
        $this->dbAdapter = $dbAdapter;
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($toArray=false)
    {
        $resultSet = $this->tableGateway->select();
        if ($toArray) {
            return $resultSet->toArray();
        }
        return $resultSet;
    }

    public function getAccount($id, $toArray=false)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_account' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }

        if ($toArray) {
            return $row->toArray();
        }
        return $row;
    }

    public function saveAccount(Account $account)
    {
        $data = array(
            'email' => $account->email,
        );

        $id = (int) $account->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getAccount($id)) {
                return $this->tableGateway->update($data, array('id_account' => $id));
            } else {
                throw new \Exception('Account id does not exist');
            }
        }
    }

    public function updateAccount($id, Account $account)
    {
        $data = array(
            'email' => $account->email,
        );

        return $this->tableGateway->update($data, array('id_account' => $id));
    }

    public function deleteAccount($id)
    {
        return $this->tableGateway->delete(array('id_account' => (int) $id));
    }
}