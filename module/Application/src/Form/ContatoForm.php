<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

/**
 * Description of ContatoForm
 *
 * @author breno
 */
class ContatoForm extends \Zend\Form\Form {
    
    public function __construct($name = null, $options = array()) {
        parent::__construct('contato', $options);
        $this->add([
            'name' => 'nome',
            'type' => 'text',
            'options' => [
                'label' => 'Nome',
            ],
        ]);
        $this->add([
            'name' => 'email',
            'type' => 'text',
            'options' => [
                'label' => 'Email',
            ],
        ]);
        $this->add([
            'name' => 'assunto',
            'type' => 'text',
            'options' => [
                'label' => 'Assunto',
            ],
        ]);
        $this->add([
            'name' => 'menssagem',
            'type' => 'Zend\Form\Element\TextArea',
            'options' => [
                'label' => 'Menssagem',
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
