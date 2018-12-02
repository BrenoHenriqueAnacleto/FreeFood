<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Form;

use Zend\Form\Form;

/**
 * Description of AdministradorForm
 *
 * @author breno
 */
class AdministradorForm extends Form {

    public function __construct($name = null) {
        // We will ignore the name provided to the constructor
        parent::__construct('administrador');

        $this->add([
            'name' => 'pessoa_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'status',
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                    '0' => 'Inativo',
                    '1' => 'Ativo',
                ),
            )
        ));
        $this->add([
            'name' => 'senha',
            'type' => 'password',
            'options' => [
                'label' => 'Senha',
            ],
        ]);
        $this->add([
            'name' => 'confirmaSenha',
            'type' => 'password',
            'options' => [
                'label' => 'Confirma senha',
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
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
            ],
        ]);
    }

}
