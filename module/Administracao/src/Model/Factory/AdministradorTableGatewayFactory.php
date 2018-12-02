<?php

namespace Administracao\Model\Factory;

use Administracao\Model\Administrador;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class AdministradorTableGatewayFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Administrador());
        return new TableGateway('administrador', $dbAdapter, null, $resultSetPrototype);
    }


}