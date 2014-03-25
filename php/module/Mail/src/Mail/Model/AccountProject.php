<?php
/**
 * Entity Account_Project
 * Lien entre l'account et les projets autorise a envoyer des issues
 * User: etienne_odiwi
 */
namespace Mail\Model;

class AccountProject
{
    public $account;
    public $project;

    public function __construct($data=null) {
        $this->account = (!empty($data['accountId'])) ? $data['accountId'] : null;
        $this->project = (!empty($data['projectId'])) ? $data['projectId'] : null;
    }

    public function exchangeArray($data) {
        if (!empty($data['id_account'])) {
            $this->account = new Account();
            $this->account->exchangeArray($data);
        }
        if (!empty($data['id_project'])) {
            $this->project = new Project();
            $this->project->exchangeArray($data);
        }
    }

    public function toArray() {
        return array (
            'account' => $this->account->toArray(),
            'project' => $this->project->toArray(),
        );
    }
}