<?php

namespace Mail;

use Mail\Library\My\Db\AccountProjectResultSet;
use Mail\Model\Account;
use Mail\Model\AccountProject;
use Mail\Model\AccountProjectTable;
use Mail\Model\AccountTable;
use Mail\Model\Project;
use Mail\Model\ProjectTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Mail\Service\MailService;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(),
            'aliases' => array(),
            'factories' => array(
                'Mail\Model\AccountTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $tableGateway = $sm->get('AccountTableGateway');
                    $table = new AccountTable($dbAdapter, $tableGateway);
                    return $table;
                },
                'AccountTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Account());
                    return new TableGateway('account', $dbAdapter, null, $resultSetPrototype);
                },
                'Mail\Model\ProjectTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $tableGateway = $sm->get('ProjectTableGateway');
                    $table = new ProjectTable($dbAdapter, $tableGateway);
                    return $table;
                },
                'ProjectTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Project());
                    return new TableGateway('project', $dbAdapter, null, $resultSetPrototype);
                },
                'Mail\Model\Account_ProjectTable' =>  function($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $tableGateway = $sm->get('Account_ProjectTableGateway');
                        $table = new AccountProjectTable($dbAdapter, $tableGateway);
                        return $table;
                    },
                'Account_ProjectTableGateway' => function ($sm) {
                        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                        $resultSetPrototype = new AccountProjectResultSet();
                        $resultSetPrototype->setArrayObjectPrototype(new AccountProject());
                        return new TableGateway('account_project', $dbAdapter, null, $resultSetPrototype);
                    },
                'Mail\Service\MailService' => function ($sm) {
                    return new MailService($sm);
                }
            ),
            'invokables' => array(),
            'services' => array(),
            'shared' => array(),
        );
    }
}