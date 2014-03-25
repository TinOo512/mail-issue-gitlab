<?php
/**
 * Account Controller
 */
namespace Mail\Controller;

use Mail\Model\Account;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class AccountController extends AbstractRestfulController
{
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        $accountService = $serviceManager->get('Mail\Model\AccountTable');
        return new JsonModel($accountService->getAccount($id, true));
    }

    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        $accountService = $serviceManager->get('Mail\Model\AccountTable');
        return new JsonModel($accountService->fetchAll(true));
    }

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();
        $accountService = $serviceManager->get('Mail\Model\AccountTable');

        $account = new Account($data);
        if ($accountService->saveAccount($account)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }

    public function update($id, $data)
    {
        $serviceManager = $this->getServiceLocator();
        $accountService = $serviceManager->get('Mail\Model\AccountTable');

        $account = new Account($data);
        if ($accountService->updateAccount($id, $account)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }

    public function delete($id)
    {
        $serviceManager = $this->getServiceLocator();
        $accountService = $serviceManager->get('Mail\Model\AccountTable');

        if ($accountService->deleteAccount($id)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }
}