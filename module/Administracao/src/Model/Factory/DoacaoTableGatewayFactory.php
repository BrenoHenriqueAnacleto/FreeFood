<?php

namespace Administracao\Model\Factory;

use Administracao\Model\Doacao;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class DoacaoTableGatewayFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Doacao());
        return new TableGateway('doacao', $dbAdapter, null, $resultSetPrototype);
    }


}