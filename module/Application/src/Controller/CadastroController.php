<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Administracao\Model\Recebedor;
use Administracao\Model\RecebedorTable;
use Administracao\Model\Doador;
use Administracao\Model\DoadorTable;
use Administracao\Model\PessoaFisica;
use Administracao\Model\PessoaFisicaTable;
use Administracao\Model\PessoaJuridica;
use Administracao\Model\PessoaJuridicaTable;
use Administracao\Model\EnderecoTable;
use Administracao\Model\Pessoa;
use Administracao\Model\PessoaTable;
use Application\Form\CadastroForm;

class CadastroController extends \Zend\Mvc\Controller\AbstractActionController {

    private $tableRecebedor;
    private $tableDoador;
    private $tablePessoa;
    private $tablePessoaFisica;
    private $tablePessoaJuridica;
    private $tableEndereco;

    public function __construct(RecebedorTable $tableRecebedor, PessoaTable $tablePessoa, PessoaFisicaTable $tablePessoaFisica, PessoaJuridicaTable $tablePessoaJuridica, EnderecoTable $tableEndereco, DoadorTable $tableDoador) {

        $this->tableRecebedor = $tableRecebedor;
        $this->tableDoador = $tableDoador;
        $this->tablePessoa = $tablePessoa;
        $this->tableEndereco = $tableEndereco;
        $this->tablePessoaFisica = $tablePessoaFisica;
        $this->tablePessoaJuridica = $tablePessoaJuridica;
    }

    public function doadorAction() {
        $form = new CadastroForm();
        $form->get('submit')->setValue('Cadastrar-se');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $doador = new Doador();
        $form->setInputFilter($doador->getInputFilter());
        $form->setData($request->getPost());
//        echo '<pre>';print_r($form); die;
        if (!$form->isValid()) {
//            error_log(json_encode($form->getMessages()));
            return ['form' => $form];
        }
        $pessoa = new Pessoa();
        $pessoaFisica = new PessoaFisica();
        $pessoaJuridica = new PessoaJuridica();
        $post = $request->getPost()->getArrayCopy();
        $pessoa->exchangeArray($post);
        $pessoaFisica->exchangeArray($post);
        $pessoaJuridica->exchangeArray($post);
        $doador->exchangeArray($form->getData());
        $pessoa->status = 1;
        $pessoaId = $this->tablePessoa->savePessoa($pessoa);

        if ($pessoaId) {
            foreach ($pessoa->endereco as $end) {
                $end->pessoa_id = $pessoaId;
            }

            $this->tableEndereco->salvarEndereco($pessoa);
//            echo '<pre>';            print_r($post); die;
            if ($post['tipo_pessoa'] == 0) {
                $pessoaFisica->pessoa_id = $pessoaId;
                $this->tablePessoaFisica->savePessoaFisica($pessoaFisica);
            } else {
                $pessoaJuridica->pessoa_id = $pessoaId;
                $this->tablePessoaJuridica->savePessoaJuridica($pessoaJuridica);
            }
            $doador->pessoa_id = $pessoaId;
            $this->tableDoador->saveDoador($doador);

            $this->flashMessenger()->addMessage('Seu cadastro foi em caminhado para analise');
        }

        return $this->redirect()->toRoute('cadastro');
    }

    public function recebedorAction() {
        $form = new CadastroForm();
        $form->get('submit')->setValue('Cadastrar-se');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $recebedor = new Recebedor();
        $form->setInputFilter($recebedor->getInputFilter());
        $form->setData($request->getPost());
//        echo '<pre>';print_r($form); die;
        if (!$form->isValid()) {
//            error_log(json_encode($form->getMessages()));
            return ['form' => $form];
        }
        $pessoa = new Pessoa();
        $pessoaFisica = new PessoaFisica();
        $pessoaJuridica = new PessoaJuridica();
        $post = $request->getPost()->getArrayCopy();
        $pessoa->exchangeArray($post);
        $pessoaFisica->exchangeArray($post);
        $pessoaJuridica->exchangeArray($post);
        $recebedor->exchangeArray($form->getData());
        $pessoa->status = 1;
        $pessoaId = $this->tablePessoa->savePessoa($pessoa);

        if ($pessoaId) {
            foreach ($pessoa->endereco as $end) {
                $end->pessoa_id = $pessoaId;
            }

            $this->tableEndereco->salvarEndereco($pessoa);
//            echo '<pre>';            print_r($post); die;
            if ($post['tipo_pessoa'] == 0) {
                $pessoaFisica->pessoa_id = $pessoaId;
                $this->tablePessoaFisica->savePessoaFisica($pessoaFisica);
            } else {
                $pessoaJuridica->pessoa_id = $pessoaId;
                $this->tablePessoaJuridica->savePessoaJuridica($pessoaJuridica);
            }
            $recebedor->pessoa_id = $pessoaId;

            $this->tableRecebedor->saveRecebedor($recebedor);
            $this->flashMessenger()->addMessage('Seu cadastro foi em caminhado para analise');
        }
        return $this->redirect()->toRoute('cadastro');
    }

}
