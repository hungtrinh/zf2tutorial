<?php
namespace Todo;

use Todo\Model\Todo;
use Todo\Model\TodoMapper;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module {

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            // 'Zend\Loader\ClassMapAutoloader' => array(
            //     __DIR__ . '/autoload_classmap.php',
            // ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Todo\Model\TodoMapper' => function ($sm) {
                    $todoTableGateway = $sm->get('TodoTableGateway');
                    // var_dump($todoTableGateway);
                    // die(__FILE__.__LINE__);
                    return new TodoMapper($todoTableGateway);
                },
                'TodoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Todo());
                    return new TableGateway('todo',
                        $dbAdapter,
                        null,
                        $resultSetPrototype
                    );
                }
            ),
        );
    }
}