<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller\Factory;

use Administracao\Controller\DoadorController;
//use Administracao\Form\PessoaForm;
use Administracao\Model\PessoaTable;
use Administracao\Model\PessoaFisicaTable;
use Administracao\Model\PessoaJuridicaTable;
use Administracao\Model\EnderecoTable;
use Administracao\Model\DoadorTable;
use Interop\Container\ContainerInterface;

/**
 * Description of DoadorFactory
 *
 * @author breno
 */
class DoadorControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $pessoaTable = $container->get(PessoaTable::class);
        $doadorTable = $container->get(DoadorTable::class);
        $pessoaFisicaTable = $container->get(PessoaFisicaTable::class);
        $pessoaJuridicaTable = $container->get(PessoaJuridicaTable::class);
        $enderecoTable = $container->get(EnderecoTable::class);
//        $pessoaForm = $container->get(PessoaForm::class);
        return new DoadorController($doadorTable, $pessoaTable,$pessoaFisicaTable,$pessoaJuridicaTable,$enderecoTable);
    }

}
