<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Administracao\Model;

use DomainException;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of PessoaJuridica
 *
 * @author breno
 */
class PessoaJuridica {
    
    private $inputFilter;
    public $ie;
    public $cnpj;
    public $nome_fantasia;
    public $pessoa_id ;
    
    public function exchangeArray(array $data) {

        $this->pessoa_id = !empty($data['pessoa_id']) ? $data['pessoa_id'] : null;
        $this->nome_fantasia = !empty($data['nome_fantasia']) ? $data['nome_fantasia'] : null;
        $this->cnpj = !empty($data['cnpj']) ? $data['cnpj'] : null;
        $this->ie = !empty($data['ie']) ? $data['ie'] : null;
    }

    public function getArrayCopy() {

        $data['pessoa_id'] = $this->pessoa_id;
        $data['nome_fantasia'] = $this->nome_fantasia;
        $data['cnpj'] = $this->cnpj;
        $data['ie'] = $this->ie;

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
            'name' => 'pessoa_id',
            'required' => true,
            'filters' => [
                ['name' => 'int'],
            ],
        ]);

        $inputFilter->add([
            'name' => 'nome_fantasia',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'ie',
            'required' => true,
        ]);
        $inputFilter->add([
            'name' => 'cnpj',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'status',
            'required' => false,
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
    
}
