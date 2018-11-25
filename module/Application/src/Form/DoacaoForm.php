<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

/**
 * Description of DoacaoForm
 *
 * @author breno
 */
class DoacaoForm extends \Zend\Form\Form {

    public function __construct() {
        // We will ignore the name provided to the constructor
        parent::__construct('doacao');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'doador_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'status',
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
