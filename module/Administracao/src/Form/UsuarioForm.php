<?php

namespace Administracao\Form;

use Zend\Form\Form;

class UsuarioForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('usuario');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name'    => 'login',
            'type'    => 'text',
            'options' => [
                'label' => 'Login',
            ],
        ]);
        $this->add([
            'name'    => 'senha',
            'type'    => 'text',
            'options' => [
                'label' => 'Senha',
            ],
        ]);
           $this->add([
            'name'    => 'email',
            'type'    => 'text',
            'options' => [
                'label' => 'Email',
            ],
        ]);
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}
