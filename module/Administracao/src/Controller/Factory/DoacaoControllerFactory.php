<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller\Factory;

use Administracao\Controller\DoacaoController;
//use Administracao\Form\PessoaForm;
use Administracao\Model\DoacaoTable;
use Administracao\Model\RecebedorTable;
use Administracao\Model\DoadorTable;
use Administracao\Model\ItemTable;
use Interop\Container\ContainerInterface;

/**
 * Description of DoadorFactory
 *
 * @author breno
 */
class DoacaoControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        $recebedorTable = $container->get(RecebedorTable::class);
        $doacaoTable    = $container->get(DoacaoTable::class);
        $doadorTable    = $container->get(DoadorTable::class);
        $itemTable      = $container->get(ItemTable::class);

        return new DoacaoController($doacaoTable,$doadorTable, $recebedorTable,$itemTable);
    }

}
