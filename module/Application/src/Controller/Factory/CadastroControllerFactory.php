<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller\Factory;

use Application\Controller\CadastroController;
//use Administracao\Form\PessoaForm;
use Administracao\Model\PessoaTable;
use Administracao\Model\PessoaFisicaTable;
use Administracao\Model\PessoaJuridicaTable;
use Administracao\Model\EnderecoTable;
use Administracao\Model\RecebedorTable;
use Administracao\Model\DoadorTable;
use Interop\Container\ContainerInterface;

/**
 * Description of RecebedorFactory
 *
 * @author breno
 */
class CadastroControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $pessoaTable = $container->get(PessoaTable::class);
        $recebedorTable = $container->get(RecebedorTable::class);
        $doadorTable = $container->get(DoadorTable::class);
        $pessoaFisicaTable = $container->get(PessoaFisicaTable::class);
        $pessoaJuridicaTable = $container->get(PessoaJuridicaTable::class);
        $enderecoTable = $container->get(EnderecoTable::class);
//        $pessoaForm = $container->get(PessoaForm::class);
        return new CadastroController($recebedorTable, $pessoaTable,$pessoaFisicaTable,$pessoaJuridicaTable,$enderecoTable,$doadorTable);
    }

}
