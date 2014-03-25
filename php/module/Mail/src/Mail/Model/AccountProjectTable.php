<?php
/**
 * Wrapper for Entity Account_Project
 * User: etienne_odiwi
 */
namespace Mail\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class AccountProjectTable
{
    protected $dbAdapter;
    protected $tableGateway;

    public function __construct(Adapter $dbAdapter, TableGateway $tableGateway)
    {
        $this->dbAdapter = $dbAdapter;
        $this->tableGateway = $tableGateway;
    }

    public function fetchByAccount($toArray=false) {
        $this->tableGateway->getResultSetPrototype()->setToArrayMod('account');
        return $this->fetchAll($toArray, 'id_account');
    }

    public function fetchByProject($toArray=false) {
        $this->tableGateway->getResultSetPrototype()->setToArrayMod('project');
        return $this->fetchAll($toArray, 'id_project');
    }

    private function fetchAll($toArray, $orderBy)
    {
        $resultSet = $this->tableGateway->select();

        $sql = new Sql($this->dbAdapter, 'account_project');
        $select = $sql->select();
        $select->join("account", "account.id_account = account_project.id_account");
        $select->join("project", "project.id_project = account_project.id_project");
        $select->order("account_project.$orderBy ASC");

        // echo $selectString = $sql->getSqlStringForSqlObject($select);exit;
        $resultSet = $this->tableGateway->selectWith($select);

        if ($toArray) {
            return $resultSet->toArray();
        }
        return $resultSet;
    }

    public function saveAccount_Project(AccountProject $accountProject)
    {
        $data = array(
            'id_account' => $accountProject->account,
            'id_project' => $accountProject->project,
        );

        return $this->tableGateway->insert($data);
    }

    public function deleteAccount_Project($ids)
    {
        $where = array(
            'id_account' => $ids[0],
            'id_project' => $ids[1],
        );

        return $this->tableGateway->delete($where);
    }
}