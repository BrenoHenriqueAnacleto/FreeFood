<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Controller\ContatoController;
use Application\Controller\CadastroController;
use Application\Controller\Factory\CadastroControllerFactory;
use Application\Controller\Factory\AuthControllerFactory;
use Application\Controller\AuthController;
use Application\Service\Factory\AuthenticationServiceFactory;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface, ServiceProviderInterface, ControllerProviderInterface
{
    const VERSION = '3.0.0dev';

     public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $container = $e->getApplication()->getServiceManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH,
            function (MvcEvent $e) use ($container) {
                $match = $e->getRouteMatch();

                $authService = $container->get(AuthenticationServiceInterface::class);
                $routeName = $match->getMatchedRouteName();
                if ($authService->hasIdentity()) {
                    return;
                } elseif (strpos($routeName, 'adm') !== false) {
                    $match->setParam('controller', AuthController::class)
                        ->setParam('action', 'login');
                }
            }, 100);


    }
    
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    public function getControllerConfig() {
        return [
            'factories' => [
                CadastroController::class => CadastroControllerFactory::class,
                AuthController::class => AuthControllerFactory::class,
                Controller\MinhaContaController::class=> Controller\Factory\MinhaContaControllerFactory::class,
                Controller\ContatoController::class => function ($container) {
                    return new Controller\ContatoController(
                    );
                },
            ],
        ];
    }
    public function getServiceConfig()
    {
        return [
            'aliases' => [
                AuthenticationService::class => AuthenticationServiceInterface::class
            ],
            'factories' => [
                AuthenticationServiceInterface::class => AuthenticationServiceFactory::class
            ]
        ];
    }
}
