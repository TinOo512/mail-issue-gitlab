<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Mail\Controller\Bash' => 'Mail\Controller\BashController',
            'Mail\Controller\Account' => 'Mail\Controller\AccountController',
            'Mail\Controller\Project' => 'Mail\Controller\ProjectController',
            'Mail\Controller\AccountProject' => 'Mail\Controller\AccountProjectController',
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'bash' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/bash[/]',
                    'defaults' => array(
                        'controller' => 'Mail\Controller\Bash',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'mail' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/mail',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Mail\Controller',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'account-project' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[account-project][/:action][/:id]',
                            'constraints' => array(
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     => '[0-9,]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Mail\Controller\AccountProject',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'mail' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);