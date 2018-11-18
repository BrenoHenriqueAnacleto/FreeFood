<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller;

use Administracao\Form\RecebedorForm;
use Administracao\Model\Recebedor;
use Administracao\Model\RecebedorTable;
use Administracao\Model\PessoaFisica;
use Administracao\Model\PessoaFisicaTable;
use Administracao\Model\PessoaJuridica;
use Administracao\Model\PessoaJuridicaTable;
use Administracao\Model\EnderecoTable;
use Administracao\Model\Pessoa;
use Administracao\Model\PessoaTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of RecebedorController
 *
 * @author breno
 */
class RecebedorController extends AbstractActionController{
    
    private $table;
    private $tablePessoa;
    private $tablePessoaFisica;
    private $tablePessoaJuridica;
    private $tableEndereco;

    public function __construct(RecebedorTable $table, PessoaTable $tablePessoa, PessoaFisicaTable $tablePessoaFisica,PessoaJuridicaTable $tablePessoaJuridica,EnderecoTable $tableEndereco)
    {
        $this->table               = $table;
        $this->tablePessoa         = $tablePessoa;
        $this->tableEndereco       = $tableEndereco;
        $this->tablePessoaFisica   = $tablePessoaFisica;
        $this->tablePessoaJuridica = $tablePessoaJuridica;
    }

    public function indexAction()
    {
        // Grab the paginator from the RecebedorTable:
        $paginator = $this->table->fetchAll(true);

        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $paginator->setItemCountPerPage(10);

        return new ViewModel(['paginator' => $paginator]);
    }

     public function addAction()
    {
        $form = new RecebedorForm();
        $form->get('submit')->setValue('Salvar');

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
        $post=$request->getPost()->getArrayCopy();
        $pessoa->exchangeArray($post);
        $pessoaFisica->exchangeArray($post);
        $pessoaJuridica->exchangeArray($post);
        $recebedor->exchangeArray($form->getData());
        
        $pessoaId=$this->tablePessoa->savePessoa($pessoa);
        
        if($pessoaId){
            foreach ($pessoa->endereco as $end){
                $end->pessoa_id=$pessoaId;
            }
            
            $this->tableEndereco->salvarEndereco($pessoa);
//            echo '<pre>';            print_r($post); die;
            if($post['tipo_pessoa']==0){
                $pessoaFisica->pessoa_id=$pessoaId;
                $this->tablePessoaFisica->savePessoaFisica($pessoaFisica);
            }else{
                $pessoaJuridica->pessoa_id=$pessoaId;
                $this->tablePessoaJuridica->savePessoaJuridica($pessoaJuridica);
            }
            $recebedor->pessoa_id=$pessoaId;
        }
        $this->table->saveRecebedor($recebedor);

        return $this->redirect()->toRoute('recebedor');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('recebedor', ['action' => 'add']);
        }

        // Retrieve the recebedor with the specified id. Doing so raises
        // an exception if the recebedor is not found, which should result
        // in redirecting to the landing page.
        try {
            $recebedor = $this->table->getRecebedor($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('recebedor', ['action' => 'index']);
        }

        $form = new RecebedorForm();
        $form->bind($recebedor);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($recebedor->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveRecebedor($recebedor);

        // Redirect to recebedor list
        return $this->redirect()->toRoute('recebedor', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('recebedor');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteRecebedor($id);
            }

            // Redirect to list of recebedors
            return $this->redirect()->toRoute('recebedor');
        }

        return [
            'id'    => $id,
            'recebedor' => $this->table->getRecebedor($id),
        ];
    }
}
