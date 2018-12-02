<?php

namespace Administracao\Model\Factory;


use Administracao\Model\DoacaoTable;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Administracao\Model;

class DoacaoTableFactory implements FactoryInterface
{


    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $tableGateway        = $container->get(Model\DoacaoTableGateway::class);
        $pessoaTable         = $container->get(Model\PessoaTable::class);

        return new DoacaoTable($tableGateway,$pessoaTable);
    }
}