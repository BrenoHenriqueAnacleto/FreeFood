<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller;

use Administracao\Form\AdministradorForm;
use Administracao\Model\Administrador;
use Administracao\Model\AdministradorTable;
use Administracao\Model\Pessoa;
use Administracao\Model\PessoaTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of AdministradorController
 *
 * @author breno
 */
class AdministradorController extends AbstractActionController{
    
    private $table;
    private $tablePessoa;

    public function __construct(AdministradorTable $table, PessoaTable $tablePessoa)
    {
        $this->table               = $table;
        $this->tablePessoa         = $tablePessoa;
    }

    public function indexAction()
    {
        // Grab the paginator from the AdministradorTable:
        $paginator = $this->table->fetchAll(true);

        // Set the current page to what has been passed in query string,
        // or to 1 if none is set, or the page is invalid:
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);

        // Set the number of items per page to 10:
        $paginator->setItemCountPerPage(10);
//        echo '<pre>';        print_r($paginator); die;
        return new ViewModel(['paginator' => $paginator]);
    }

    public function addAction()
    {
        $form = new AdministradorForm();
        $form->get('submit')->setValue('Salvar');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }
        
        $administrador = new Administrador();
        $form->setInputFilter($administrador->getInputFilter());
        $form->setData($request->getPost());
//        echo '<pre>';print_r($form); die;
        if (!$form->isValid()) {
//            error_log(json_encode($form->getMessages()));
            return ['form' => $form];
        }
        $pessoa = new Pessoa();
        $post=$request->getPost()->getArrayCopy();
        $pessoa->exchangeArray($post);
        $administrador->exchangeArray($form->getData());
        
        $pessoaId=$this->tablePessoa->savePessoa($pessoa);
        
        if($pessoaId){
            $administrador->pessoa_id=$pessoaId;
        }
        $this->table->saveAdministrador($administrador);

        return $this->redirect()->toRoute('administrador');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('administrador', ['action' => 'add']);
        }

        // Retrieve the administrador with the specified id. Doing so raises
        // an exception if the administrador is not found, which should result
        // in redirecting to the landing page.
        try {
            $administrador = $this->table->getAdministrador($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('administrador', ['action' => 'index']);
        }

        $form = new AdministradorForm();
//        echo '<pre>';        print_r($administrador); die;
        $form->bind($administrador);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($administrador->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $pessoa = new Pessoa();
        $post=$request->getPost()->getArrayCopy();
        $pessoa->exchangeArray($post);
        $administrador->exchangeArray($form->getData());
        
        $pessoaId=$this->tablePessoa->savePessoa($pessoa);
        
        if($pessoaId){
           
            $administrador->pessoa_id=$pessoaId;
        }
        $this->table->saveAdministrador($administrador);

        // Redirect to administrador list
        return $this->redirect()->toRoute('administrador', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('administrador');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteAdministrador($id);
            }

            // Redirect to list of administradors
            return $this->redirect()->toRoute('administrador');
        }

        return [
            'id'    => $id,
            'administrador' => $this->table->getAdministrador($id),
        ];
    }
}
