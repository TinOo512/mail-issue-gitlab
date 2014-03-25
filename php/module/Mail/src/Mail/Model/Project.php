<?php
/**
 * Entity Project
 * Contient toutes les infos relatif au project gitlab
 * User: etienne_odiwi
 */
namespace Mail\Model;

class Project
{
    public $id;
    public $aliasName;
    public $folderName;
    public $projectName;
    public $idGitlabProject;

    public function __construct($data=null) {
        $this->id              = (!empty($data['id'])) ? $data['id'] : null;
        $this->aliasName       = (!empty($data['aliasName'])) ? $data['aliasName'] : null;
        $this->folderName      = (!empty($data['folderName'])) ? $data['folderName'] : null;
        $this->projectName     = (!empty($data['projectName'])) ? $data['projectName'] : null;
        $this->idGitlabProject = (!empty($data['idGitlabProject'])) ? $data['idGitlabProject'] : null;
    }

    public function exchangeArray($data)
    {
        $this->id              = (!empty($data['id_project'])) ? $data['id_project'] : null;
        $this->aliasName       = (!empty($data['alias_name'])) ? $data['alias_name'] : null;
        $this->folderName      = (!empty($data['folder_name'])) ? $data['folder_name'] : null;
        $this->projectName     = (!empty($data['project_name'])) ? $data['project_name'] : null;
        $this->idGitlabProject = (!empty($data['id_gitlab_project'])) ? $data['id_gitlab_project'] : null;
    }

    public function toArray() {
        return array (
            'id' => $this->id,
            'aliasName' => $this->aliasName,
            'folderName' => $this->folderName,
            'projectName' => $this->projectName,
            'idGitlabProject' => $this->idGitlabProject,
        );
    }
}