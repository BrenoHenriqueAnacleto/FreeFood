<?php

namespace Administracao\Form;

use Zend\Form\Form;

class PessoaForm extends Form {

    public function __construct($name = null) {
        // We will ignore the name provided to the constructor
        parent::__construct('pessoa');

        $this->add([
            'name' => 'usuario_id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'nome',
            'type' => 'text',
            'options' => [
                'label' => 'Nome',
            ],
        ]);
        $this->add([
            'name' => 'cpf',
            'type' => 'text',
            'options' => [
                'label' => 'CPF',
            ],
        ]);
        $this->add([
            'name' => 'cnpj',
            'type' => 'text',
            'options' => [
                'label' => 'CNPJ',
            ],
        ]);
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'tipo_pessoa',
            'options' => array(
                'value_options' => array(
                '0' => 'Pessoa Fisica',
                '1' => 'Pessoa Juridica',
            ),
               
        )));
        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'tipo_usuario',
            'options' => array(
            'value_options' => array(
                '0' => 'Doador',
                '1' => 'Receptor',
            ),

        )));
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
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id' => 'submitbutton',
            ],
        ]);
    }

}
