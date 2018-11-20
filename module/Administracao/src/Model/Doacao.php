<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Model;

use Administracao\Model\Item;
/**
 * Description of Doacao
 *
 * @author breno
 */
class Doacao{
    
    public $id;
    public $titulo;
    public $descricao;
    public $recebedor_id;
    public $doador_id;
    public $itens;
   
    public function __construct() {
    
        $this->itens = array();
    }
    
      public function exchangeArray($dados) {

        $this->id            = (!empty($dados['id']))            ? $dados['id']                    : null;
        $this->doador_id     = (!empty($dados['doador_id']))     ? $dados['doador_id']             : null;
        $this->recebedor_id  = (!empty($dados['recebedor_id']))  ? $dados['recebedor_id']          : null;
        $this->titulo        = (!empty($dados['titulo']))        ? $dados['titulo']                : null;
        $this->descricao     = (!empty($dados['descricao']))     ? strip_tags($dados['descricao']) : null;

        $this->SetaItens($dados);

        return $this;
    }

    public function SetaItens($dados) {

        if (isset($dados['item'])) {

            foreach ($dados['item'] as $key => $value) {

                $item = new Item();

                $item->exchangeArray($value);

                $this->itens[] = $item;
            }
        }
    }
        public function getArrayCopy() {

        $dados = [
            'titulo'       => $this->titulo,
            'descricao'    => $this->descricao,
            'recebedor_id' => $this->recebedor_id,
            'doador_id'    => $this->doador_id,
        ];

        if (!is_null($this->id)) {

            $dados['id'] = $this->id;
        }

        return $dados;
    }

    public function getArrayCopySingle() {

        $dados = $this->getArrayCopy();

        unset($dados['itens']);

        return $dados;
    }
    
     public function getInputFilter() {
        $inputFilter = new \Zend\InputFilter\InputFilter();

        $inputFilter->add(array(
            'name' => 'id',
            'required' => true,
            'filters' => array(
                array('name' => 'Int'),
            ),
        ));
        $inputFilter->add(array(
            'name' => 'itens',
            'required' => false,
        ));
        $inputFilter->add(array(
            'name' => 'titulo',
            'required' => false,
        ));
        $inputFilter->add(array(
            'name' => 'descricao',
            'required' => false,
        ));
        $inputFilter->add(array(
            'name' => 'recebedor_id',
            'required' => false,
        ));
        $inputFilter->add(array(
            'name' => 'doador_id',
            'required' => false,
        ));
        
        $this->inputFilter = $inputFilter;

        return $this->inputFilter;
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter) {
        
    }

}
