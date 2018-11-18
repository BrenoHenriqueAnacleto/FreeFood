<?php

namespace Administracao\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

class Endereco {

    private $inputFilter;
    public $id;
    public $rua;
    public $bairro;
    public $cidade;
    public $numero;
    public $cep;
    public $complemento;
    public $pessoa_id;

    public function exchangeArray(array $data) {

        $this->id = !empty($data['id']) ? $data['id'] : null;
        $this->rua = !empty($data['rua']) ? $data['rua'] : null;
        $this->bairro = !empty($data['bairro']) ? $data['bairro'] : null;
        $this->cidade = !empty($data['cidade']) ? $data['cidade'] : null;
        $this->numero = !empty($data['numero']) ? $data['numero'] : null;
        $this->cep = !empty($data['cep']) ? $data['cep'] : null;
        $this->complemento = !empty($data['complemento']) ? $data['complemento'] : null;
        $this->pessoa_id = !empty($data['pessoa_id']) ? $data['pessoa_id'] : null;
    }

    public function getArrayCopy() {

        $data['id'] = $this->id;
        $data['rua'] = $this->rua;
        $data['bairro'] = $this->bairro;
        $data['cidade'] = $this->cidade;
        $data['numero'] = $this->numero;
        $data['cep'] = $this->cep;
        $data['complemento'] = $this->complemento;
        $data['pessoa_id'] = $this->pessoa_id;

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
            'name' => 'pessoa_id',
            'required' => true,
            'filters' => [
                ['name' => 'int'],
            ],
        ]);
        $inputFilter->add([
            'name' => 'rua',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'cidade',
            'required' => true,
        ]);
        $inputFilter->add([
            'name' => 'bairro',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'numero',
            'required' => false,
        ]);

        $inputFilter->add([
            'name' => 'cep',
            'required' => false,
        ]);
        $inputFilter->add([
            'name' => 'complemento',
            'required' => false,
        ]);
        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

}
