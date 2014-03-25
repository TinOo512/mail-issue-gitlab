<?php
/**
 * Entity Account
 * Contient l'email du compte autorise
 * User: etienne_odiwi
 */
namespace Mail\Model;

class Account
{
    public $id;
    public $email;

    public function __construct($data=null) {
        $this->id    = (!empty($data['id'])) ? $data['id'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
    }

    public function exchangeArray($data) {
        $this->id    = (!empty($data['id_account'])) ? $data['id_account'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
    }

    public function toArray() {
        return array (
            'id' => $this->id,
            'email' => $this->email,
        );
    }
}