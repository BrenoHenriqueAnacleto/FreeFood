<?php

namespace Administracao;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\UsuarioTable::class        => function ($container) {
                    $tableGateway = $container->get('Model\UsuarioTableGateway');

                    return new Model\UsuarioTable($tableGateway);
                },
                'Model\UsuarioTableGateway' => function ($container) {
                    $dbAdapter          = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Usuario());

                    return new TableGateway('usuario', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\UsuarioController::class => function ($container) {
                    return new Controller\UsuarioController(
                        $container->get(Model\UsuarioTable::class)
                    );
                },
            ],
        ];
    }
     public function onBootstrap($e)
    {
        // Register a dispatch event
        $app = $e->getParam('application');
        $app->getEventManager()->attach('dispatch', array($this, 'setLayout'));
    }

    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayout($e)
    {
        $matches    = $e->getRouteMatch();
        $controller = $matches->getParam('controller');
        if (false === strpos($controller, __NAMESPACE__)) {
            // not a controller from this module
            return;
        }

        // Set the layout template
        $viewModel = $e->getViewModel();
        $viewModel->setTemplate('layout/layout-adm.phtml');
    }
}
