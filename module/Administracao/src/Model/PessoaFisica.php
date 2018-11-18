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
 * Description of PessoaFisica
 *
 * @author breno
 */
class PessoaFisica {
    
    private $inputFilter;
    public  $rg;
    public  $cpf;
    public  $nome;
    public  $pessoa_id;
    
    public function exchangeArray(array $data) {

        $this->pessoa_id = !empty($data['pessoa_id']) ? $data['pessoa_id'] : null;
        $this->nome = !empty($data['nome']) ? $data['nome'] : null;
        $this->cpf = !empty($data['cpf']) ? $data['cpf'] : null;
        $this->rg = !empty($data['rg']) ? $data['rg'] : null;
    }

    public function getArrayCopy() {

        $data['pessoa_id'] = $this->pessoa_id;
        $data['nome'] = $this->nome;
        $data['cpf'] = $this->cpf;
        $data['rg'] = $this->rg;

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
            'name' => 'nome',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'rg',
            'required' => true,
        ]);
        $inputFilter->add([
            'name' => 'cpf',
            'required' => true,
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }


}
