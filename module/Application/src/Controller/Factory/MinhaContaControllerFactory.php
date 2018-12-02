<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Application\Controller\MinhaContaController;
use Zend\Authentication\AuthenticationServiceInterface;

class MinhaContaControllerFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $authService         = $container->get(AuthenticationServiceInterface::class);
        $pessoaTable         = $container->get(\Administracao\Model\PessoaTable::class);
        $doadorTable         = $container->get(\Administracao\Model\DoadorTable::class);
        $recebedorTable      = $container->get(\Administracao\Model\RecebedorTable::class);
        $doacaoTable         = $container->get(\Administracao\Model\DoacaoTable::class);
        $itemTable           = $container->get(\Administracao\Model\ItemTable::class);
        $administradorTable  = $container->get(\Administracao\Model\AdministradorTable::class);
        
        return new MinhaContaController($authService,$pessoaTable,$doadorTable,$recebedorTable,$doacaoTable,$itemTable,$administradorTable);
    }


}