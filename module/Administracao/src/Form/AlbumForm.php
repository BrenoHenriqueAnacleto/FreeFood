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
            'name'    => 'title',
            'type'    => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
        $this->add([
            'name'    => 'artist',
            'type'    => 'text',
            'options' => [
                'label' => 'Artist',
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
