<?php

namespace Administracao\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Usuario {

    private $inputFilter;
    public $id;
    public $login;
    public $senha;
    public $email;
    public $token;

    public function exchangeArray(array $data) {

        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->login = !empty($data['login']) ? $data['login'] : null;
        $this->senha = !empty($data['senha']) ? $data['senha'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
        $this->token = !empty($data['token']) ? $data['token'] : null;
    }

    public function getArrayCopy() {

        $data['id'] = $this->id;
        $data['login'] = $this->login;
        $data['senha'] = $this->senha;
        $data['email'] = $this->email;
        $data['token'] = $this->token;

        return $data;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new DomainException(sprintf(
                '%s does not allow injection of an alternate input filter', __CLASS__
        ));
    }

    public function getInputFilter() {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => 'int'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'login',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        $inputFilter->add([
            'name' => 'senha',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'token',
            'required' => false,
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

}
