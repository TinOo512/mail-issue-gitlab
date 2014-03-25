<?php
/**
 * AccountProject Controller
 */
namespace Mail\Controller;

use Mail\Model\AccountProject;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class AccountProjectController extends AbstractRestfulController
{
    public function getList()
    {
        $mod = $this->params()->fromQuery('mod', null);

        $serviceManager = $this->getServiceLocator();
        $accountProjectService = $serviceManager->get('Mail\Model\Account_ProjectTable');
        if ($mod === 'account') {
            return new JsonModel($accountProjectService->fetchByAccount(true));
        } else {
            return new JsonModel($accountProjectService->fetchByProject(true));
        }
    }

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();
        $accountProjectService = $serviceManager->get('Mail\Model\Account_ProjectTable');

        $accountProject = new AccountProject($data);
        if ($accountProjectService->saveAccount_Project($accountProject)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }

    public function delete($id)
    {
        $serviceManager = $this->getServiceLocator();
        $accountProjectService = $serviceManager->get('Mail\Model\Account_ProjectTable');

        $ids = preg_split('/,/', $id);
        if ($accountProjectService->deleteAccount_Project($ids)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }
}