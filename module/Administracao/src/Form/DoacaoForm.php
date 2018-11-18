<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Form;

/**
 * Description of DoacaoForm
 *
 * @author breno
 */
class DoacaoForm extends \Zend\Form\Form {

    public function __construct($name = null,$doadores=[],$recebedores=[]) {
        // We will ignore the name provided to the constructor
        parent::__construct('doacao');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'doadores',
            'type' => 'hidden',
        ]);
        $this->add(array(
            'name' => 'doador',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Doador',
                'value_options' => $doadores
            )
        ));
        $this->add(array(
            'name' => 'recebedor',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Recebedor',
                'value_options' => $recebedores
            )
        ));
        $this->add([
            'name' => 'recebedores',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'descricao',
            'type' => 'text',
            'options' => [
                'label' => 'Descricao',
            ],
        ]);
        $this->add([
            'name' => 'titulo',
            'type' => 'text',
            'options' => [
                'label' => 'Titulo',
            ],
        ]);
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
            ],
        ]);
    }

}
