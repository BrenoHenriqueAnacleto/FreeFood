<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller\Factory;

use Administracao\Controller\RecebedorController;
//use Administracao\Form\PessoaForm;
use Administracao\Model\PessoaTable;
use Administracao\Model\PessoaFisicaTable;
use Administracao\Model\PessoaJuridicaTable;
use Administracao\Model\EnderecoTable;
use Administracao\Model\RecebedorTable;
use Interop\Container\ContainerInterface;

/**
 * Description of RecebedorFactory
 *
 * @author breno
 */
class RecebedorControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $pessoaTable = $container->get(PessoaTable::class);
        $recebedorTable = $container->get(RecebedorTable::class);
        $pessoaFisicaTable = $container->get(PessoaFisicaTable::class);
        $pessoaJuridicaTable = $container->get(PessoaJuridicaTable::class);
        $enderecoTable = $container->get(EnderecoTable::class);
//        $pessoaForm = $container->get(PessoaForm::class);
        return new RecebedorController($recebedorTable, $pessoaTable,$pessoaFisicaTable,$pessoaJuridicaTable,$enderecoTable);
    }

}
