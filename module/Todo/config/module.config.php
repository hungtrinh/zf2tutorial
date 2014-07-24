<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Todo\Controller\Todo' => 'Todo\Controller\TodoController',
        )
    ),

    'router' => array(
        'routes' => array(
            'todo' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/todo[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Todo\Controller\Todo',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'todo' => __DIR__ . "/../view",
        )
    ),
);