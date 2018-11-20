<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Administracao\Controller;

use Administracao\Model\DoacaoTable;
use Administracao\Model\RecebedorTable;
use Administracao\Model\DoadorTable;
use Administracao\Model\ItemTable;
use Administracao\Model\Doacao;
use Administracao\Model\Item;
use Administracao\Form\DoacaoForm;
use Zend\View\Model\ViewModel;
class DoacaoController extends \Zend\Mvc\Controller\AbstractActionController{
    
    public $recebedorTable;
    public $doadorTable;
    public $doacaoTable;
    public $itemTable;


    public function __construct(DoacaoTable $doacaoTable,DoadorTable $doadorTable,RecebedorTable $recebedorTable,ItemTable $itemTable) {
        
        $this->doacaoTable=$doacaoTable;
        $this->doadorTable=$doadorTable;
        $this->recebedorTable=$recebedorTable;
        $this->itemTable=$itemTable;
    }


    public function indexAction()
    {
        // Grab the paginator from the DoadorTable:
        $paginator = $this->doacaoTable->fetchAll(true);
        

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
        $doadores= $this->doadorTable->getDoadores();
        $recebedores =$this->recebedorTable->getRecebedores();
        $form = new DoacaoForm($doadores,$recebedores);
        $form->get('submit')->setValue('Salvar');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }
        
        $doacao = new Doacao();
        $form->setInputFilter($doacao->getInputFilter());
        $form->setData($request->getPost());
//        echo '<pre>';print_r($form); die;
        if (!$form->isValid()) {
//            error_log(json_encode($form->getMessages()));
            return ['form' => $form];
        }
//        $item = new Item();
        
        $post=$request->getPost()->getArrayCopy();
        
//        $item->exchangeArray($post);
        
        $doacao->exchangeArray($post);
//        echo '<pre>'; print_r($doacao); die;
        $id=$this->doacaoTable->saveDoacao($doacao);
        
        if($id){
//            
            foreach ($doacao->itens as $item){
                $item->doacao_id=$id;
            }
//            echo '<pre>'; print_r($doacao); die;
            $this->itemTable->SalvarItemDoacao($doacao);
        }

        return $this->redirect()->toRoute('doacao');
    }

}
