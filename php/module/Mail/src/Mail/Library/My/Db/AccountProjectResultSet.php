<?php
/**
 * AccountProjectResultSet class
 * User: etienne_odiwi
 */
namespace Mail\Library\My\Db;

use ArrayObject;
use Exception;
use Zend\Db\ResultSet\ResultSet;

class AccountProjectResultSet extends ResultSet
{

    private $toArrayMod;

    /**
     * Cast result set to array of arrays
     *
     * ICI nous sommes dans un cas particulier avec les accounts et les projects pour organiser les projects enfant de leur account
     *
     * @return array
     * @throws \Exception
     */
    public function toArray()
    {
        $return = array();
        $lastParentId = null;
        if ($this->toArrayMod === "account") {
            foreach ($this as $row) {
                if ($row->account->id != $lastParentId) {
                    $return[]['account'] = $row->account;
                }
                $last = count($return)-1;

                if (is_array($row)) {
                    $return[$last]['projects'][] = $row->project;
                } elseif (method_exists($row, 'toArray')) {
                    $return[$last]['projects'][] = $row->project->toArray();
                } elseif ($row instanceof ArrayObject) {
                    $return[$last]['projects'][] = $row->project->getArrayCopy();
                } else {
                    throw new Exception(
                        'Rows as part of this DataSource, with type ' . gettype($row) . ' cannot be cast to an array'
                    );
                }
                $lastParentId = $row->account->id;
            }
        } else if ($this->toArrayMod === "project") {
            foreach ($this as $row) {
                if ($row->project->id != $lastParentId) {
                    $return[]['project'] = $row->project;
                }
                $last = count($return)-1;

                if (is_array($row)) {
                    $return[$last]['accounts'][] = $row->account;
                } elseif (method_exists($row, 'toArray')) {
                    $return[$last]['accounts'][] = $row->account->toArray();
                } elseif ($row instanceof ArrayObject) {
                    $return[$last]['accounts'][] = $row->account->getArrayCopy();
                } else {
                    throw new Exception(
                        'Rows as part of this DataSource, with type ' . gettype($row) . ' cannot be cast to an array'
                    );
                }
                $lastParentId = $row->project->id;
            }
        } else {
            throw new Exception(
                'Please set the variable $toArrayMod before call the toArray function'
            );
        }

        return $return;
    }

    public function setToArrayMod($toArrayMod) {
        $this->toArrayMod = $toArrayMod;
    }

    public function getToArrayMod() {
        return $this->toArrayMod;
    }

}