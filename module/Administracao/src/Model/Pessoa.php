<?php

namespace Administracao\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Pessoa {

    private $inputFilter;
    public $id;
    public $email;
    public $senha;
    public $token;
    public $status;
    public $endereco;
    
    public function __construct() {
        

        $this->endereco = array();
    }

    public function exchangeArray(array $data) {
        
        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->email = !empty($data['email']) ? $data['email'] : null;
        $this->senha = !empty($data['senha']) ? $data['senha'] : null;
        $this->token = !empty($data['token']) ? $data['token'] : null;
        $this->status = !empty($data['status']) ? $data['status'] : null;
        $this->endereco = !empty($data['endereco']) ? $data['endereco'] : null;
        $this->SetaEndereco($data);
        
        return $this;
    }
    
    public function SetaEndereco($dados) {
        
        if (isset($dados['endereco'])) {

            foreach ($dados['endereco'] as $key => $value) {

                $endereco = new Endereco();

                $endereco->exchangeArray($value);

                $this->endereco[] = $endereco;
            }
        }
    }

    public function getArrayCopy() {

        $data['id'] = $this->id;
        $data['email'] = $this->email;
        $data['senha'] = $this->senha;
        $data['token'] = $this->token;
        $data['status'] = $this->status;

        return $data;
    }
    public function getArrayCopySingle() {
        
        $dados = $this->getArrayCopy();
        
        unset($dados['endereco']);

        return $dados;
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
            'name' => 'token',
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
            'name' => 'status',
            'required' => false,
        ]);

        $inputFilter->add([
            'name' => 'endereco',
            'required' => false,
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

}
