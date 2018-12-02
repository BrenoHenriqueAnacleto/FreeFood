<?php

namespace Administracao\Controller\Factory;

use Interop\Container\ContainerInterface;
use Administracao\Controller\IndexController;
use Zend\Authentication\AuthenticationServiceInterface;

class IndexControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $authService    = $container->get(AuthenticationServiceInterface::class);
        $pessoaTable    = $container->get(\Administracao\Model\PessoaTable::class);
        $administradorTable  = $container->get(\Administracao\Model\AdministradorTable::class);

        return new IndexController($authService,$pessoaTable,$administradorTable);
    }


}