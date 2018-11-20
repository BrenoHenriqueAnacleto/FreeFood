<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Model;

/**
 * Description of Item
 *
 * @author breno
 */
class Item {

    public $id;
    public $nome;
    public $descricao;
    public $quantidade;
    public $valor_unitario;
    public $doacao_id;
    
    public function exchangeArray(array $data) {
        error_log(json_encode($data));
        $this->id             = !empty($data['id'])             ? $data['id']             : null;
        $this->nome           = !empty($data['nome'])           ? $data['nome']           : null;
        $this->descricao      = !empty($data['descricao'])      ? $data['descricao']      : null;
        $this->quantidade     = !empty($data['quantidade'])     ? $data['quantidade']     : null;
        $this->valor_unitario = !empty($data['valor_unitario']) ? $data['valor_unitario'] : null;
        $this->doacao_id      = !empty($data['doacao_id'])      ? $data['doacao_id'] : null;
       
        return $this;
    }
    
    public function getArrayCopy() {

        $data['id']             = $this->id;
        $data['nome']           = $this->nome;
        $data['descricao']      = $this->descricao;
        $data['quantidade']     = $this->quantidade;
        $data['valor_unitario'] = $this->valor_unitario;
        $data['doacao_id']      = $this->doacao_id;
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
            'name' => 'nome',
            'required' => true,
        ]);
        
        $inputFilter->add([
            'name' => 'descricao',
            'required' => true,
        ]);
        
        $inputFilter->add([
            'name' => 'quantidade',
            'required' => true,
        ]);

        $inputFilter->add([
            'name' => 'valor_unitario',
            'required' => false,
        ]);
        $inputFilter->add([
            'name' => 'doacao_id',
            'required' => false,
        ]);

        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }
    
}
