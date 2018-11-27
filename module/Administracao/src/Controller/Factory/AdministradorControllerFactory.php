<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller\Factory;

use Administracao\Controller\AdministradorController;
//use Administracao\Form\PessoaForm;
use Administracao\Model\PessoaTable;
use Administracao\Model\AdministradorTable;
use Interop\Container\ContainerInterface;

/**
 * Description of AdministradorFactory
 *
 * @author breno
 */
class AdministradorControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $pessoaTable = $container->get(PessoaTable::class);
        $administradorTable = $container->get(AdministradorTable::class);

//        $pessoaForm = $container->get(PessoaForm::class);
        return new AdministradorController($administradorTable, $pessoaTable);
    }

}
