<?php
/**
 * Wrapper for Entity Project
 * User: etienne_odiwi
 */
namespace Mail\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

class ProjectTable
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

    public function getProject($id, $toArray=false)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id_project' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }

        if ($toArray) {
            return $row->toArray();
        }
        return $row;
    }

    public function saveProject(Project $project)
    {
        $data = array(
            'alias_name' => $project->aliasName,
            'folder_name' => $project->folderName,
            'project_name'  => $project->projectName,
            'id_gitlab_project'  => $project->idGitlabProject,
        );

        $id = (int) $project->id;
        if ($id == 0) {
            return $this->tableGateway->insert($data);
        } else {
            if ($this->getProject($id)) {
                return $this->tableGateway->update($data, array('id_project' => $id));
            } else {
                throw new \Exception('Project id does not exist');
            }
        }
    }

    public function updateProject($id, Project $project)
    {
        $data = array(
            'alias_name' => $project->aliasName,
            'folder_name' => $project->folderName,
            'project_name'  => $project->projectName,
            'id_gitlab_project'  => $project->idGitlabProject,
        );

        return $this->tableGateway->update($data, array('id_project' => $id));
    }

    public function deleteProject($id)
    {
        return $this->tableGateway->delete(array('id_project' => (int) $id));
    }

    public function getIdGitlabProjectWithMails($mailFrom, $mailTo) {
        $sql = new Sql($this->dbAdapter, 'project');
        $select = $sql->select();
        $select->join("account_project", "account_project.id_project = project.id_project");
        $select->join("account", "account.id_account = account_project.id_account");
        $select->where(array('email' => $mailFrom, 'alias_name' => $mailTo));

        // echo $selectString = $sql->getSqlStringForSqlObject($select);
        return $this->tableGateway->selectWith($select);
    }

    public function getFolderNameWithAlias($aliasName) {
        $sql = new Sql($this->dbAdapter, 'project');
        $select = $sql->select();
        $select->where(array('alias_name' => $aliasName));

        // $selectString = $sql->getSqlStringForSqlObject($select);
        return $this->tableGateway->selectWith($select);
    }
}