<?php

namespace Administracao\Model\Factory;

use Administracao\Model\Recebedor;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class RecebedorTableGatewayFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Recebedor());
        return new TableGateway('recebedor', $dbAdapter, null, $resultSetPrototype);
    }


}