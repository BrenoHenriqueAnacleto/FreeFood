<?php

namespace Administracao;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Administracao\Controller\Factory\DoadorControllerFactory;
use Administracao\Controller\DoadorController;
use Administracao\Controller\Factory\IndexControllerFactory;
use Administracao\Controller\Factory\RecebedorControllerFactory;
use Administracao\Controller\RecebedorController;
use Administracao\Controller\Factory\AdministradorControllerFactory;
use Administracao\Controller\AdministradorController;
use Administracao\Controller\Factory\DoacaoControllerFactory;
use Administracao\Controller\DoacaoController;
use Administracao\Model\Factory\DoacaoTableFactory;
use Administracao\Model\Factory\DoacaoTableGatewayFactory;
use Administracao\Model\Factory\DoadorTableFactory;
use Administracao\Model\Factory\DoadorTableGatewayFactory;
use Administracao\Model\Factory\RecebedorTableFactory;
use Administracao\Model\Factory\RecebedorTableGatewayFactory;
use Administracao\Model\Factory\AdministradorTableFactory;
use Administracao\Model\Factory\AdministradorTableGatewayFactory;

class Module implements ConfigProviderInterface, ServiceProviderInterface, ControllerProviderInterface {

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return [
            'factories' => [
                Model\DoadorTable::class => DoadorTableFactory::class,
                Model\DoadorTableGateway::class => DoadorTableGatewayFactory::class,
                Model\PessoaTable::class => function ($container) {
                    $tableGateway = $container->get('Model\PessoaTableGateway');

                    return new Model\PessoaTable($tableGateway);
                },
                'Model\PessoaTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Pessoa());

                    return new TableGateway('pessoa', $dbAdapter, null, $resultSetPrototype);
                }, Model\PessoaFisicaTable::class => function ($container) {
                    $tableGateway = $container->get('Model\PessoaFisicaTableGateway');

                    return new Model\PessoaFisicaTable($tableGateway);
                },
                'Model\PessoaFisicaTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PessoaFisica());

                    return new TableGateway('pessoa_fisica', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PessoaJuridicaTable::class => function ($container) {
                    $tableGateway = $container->get('Model\PessoaJuridicaTableGateway');

                    return new Model\PessoaJuridicaTable($tableGateway);
                },
                'Model\PessoaJuridicaTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PessoaJuridica());

                    return new TableGateway('pessoa_juridica', $dbAdapter, null, $resultSetPrototype);
                },
                Model\EnderecoTable::class => function ($container) {
                    $tableGateway = $container->get('Model\EnderecoTableGateway');

                    return new Model\EnderecoTable($tableGateway);
                },
                'Model\EnderecoTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Endereco());

                    return new TableGateway('endereco', $dbAdapter, null, $resultSetPrototype);
                },
                Model\ItemTable::class => function ($container) {
                    $tableGateway = $container->get('Model\ItemTableGateway');

                    return new Model\ItemTable($tableGateway);
                },
                'Model\ItemTableGateway' => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Item());

                    return new TableGateway('item', $dbAdapter, null, $resultSetPrototype);
                },
                Model\RecebedorTable::class => RecebedorTableFactory::class,
                Model\RecebedorTableGateway::class => RecebedorTableGatewayFactory::class,
                Model\DoacaoTable::class => DoacaoTableFactory::class,
                Model\DoacaoTableGateway::class => DoacaoTableGatewayFactory::class,
                Model\AdministradorTable::class => AdministradorTableFactory::class,
                Model\AdministradorTableGateway::class => AdministradorTableGatewayFactory::class,
            ],
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                DoadorController::class => DoadorControllerFactory::class
                , Controller\PessoaController::class => function ($container) {
                    return new Controller\PessoaController(
                            $container->get(Model\PessoaTable::class)
                    );
                }, Controller\IndexController::class => IndexControllerFactory::class,
                RecebedorController::class => RecebedorControllerFactory::class,
                DoacaoController::class => DoacaoControllerFactory::class,
                AdministradorController::class => AdministradorControllerFactory::class,
            ],
        ];
    }

    public function onBootstrap($e) {
        // Register a dispatch event
        $app = $e->getParam('application');
        $app->getEventManager()->attach('dispatch', array($this, 'setLayout'));
    }

    /**
     * @param  \Zend\Mvc\MvcEvent $e The MvcEvent instance
     * @return void
     */
    public function setLayout($e) {
        $matches = $e->getRouteMatch();
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
