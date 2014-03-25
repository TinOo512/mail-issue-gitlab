<?php
/**
 * Bash Controller
 */
namespace Mail\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class BashController extends AbstractActionController
{
    /**
     * Index Action
     * @return array|void
     */
    public function indexAction()
    {
        $serviceManager = $this->getServiceLocator();
        $mailService = $serviceManager->get('Mail\Service\MailService');
        $mailService->syncIssueWithMail();
    }
}