<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller\Factory;

use Application\Controller\ContatoController;
use Interop\Container\ContainerInterface;

/**
 * Description of RecebedorFactory
 *
 * @author breno
 */
class ContatoControllerFactory implements \Zend\ServiceManager\Factory\FactoryInterface {

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        
        return new ContatoController();
    }

}
