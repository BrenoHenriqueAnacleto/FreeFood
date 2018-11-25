<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\View\Model\ViewModel;
use Application\Form\DoacaoForm;
use Administracao\Model\Doacao;

/**
 * Description of MinhaContaController
 *
 * @author breno
 */
class MinhaContaController extends \Zend\Mvc\Controller\AbstractActionController {

    private $authService;
    public $pessoaTable;
    public $doadorTable;
    public $recebedorTable;
    public $doacaoTable;
    public $itemTable;

    public function __construct(AuthenticationServiceInterface $authService, \Administracao\Model\PessoaTable $pessoaTable, \Administracao\Model\DoadorTable $doadorTable, \Administracao\Model\RecebedorTable $recebedorTable, \Administracao\Model\DoacaoTable $doacaoTable, \Administracao\Model\ItemTable $itemTable) {

        $this->authService    = $authService;
        $this->doadorTable    = $doadorTable;
        $this->pessoaTable    = $pessoaTable;
        $this->recebedorTable = $recebedorTable;
        $this->doacaoTable    = $doacaoTable;
        $this->itemTable      = $itemTable;
    }

    public function indexAction() {

        if ($this->authService->hasIdentity()) {

            $email = $this->authService->getIdentity();
            $pessoa = $this->pessoaTable->getPessoaPorEmail($email);
            $doador = $this->doadorTable->getDoadorPorPessoaId($pessoa->id);
            $recebedor = $this->recebedorTable->getRecebedorPorPessoaId($pessoa->id);

            if ($doador) {

                return $this->redirect()->toRoute('minha-conta', ['controller' => 'minha-conta', 'action' => 'doador']);
            }
            if ($recebedor) {

                return $this->redirect()->toRoute('minha-conta', ['controller' => 'minha-conta', 'action' => 'recebedor']);
            }
        }
        return $this->redirect()->toRoute('home');
    }

    public function doadorAction() {

        $doacoes = $this->doacaoTable->getTodos();
//        echo '<pre>';        print_r($doacoes); die;
        $dados = [
            'doacoes' => $doacoes
        ];
        $viewModel = new ViewModel($dados);
        return $viewModel;
    }

    public function doarAction() {

        $id = $this->params()->fromRoute("id", null);
        $doacoes = $this->doacaoTable->getDoacao($id);
        $dados = [
            'doacoes' => $doacoes
        ];
        $viewModel = new ViewModel($dados);
        return $viewModel;
    }

    public function salvarAction() {

        $id = $this->params()->fromRoute("id", null);
        $doacoes = $this->doacaoTable->getDoacao($id);
        $doacoes->status = 1;
        $email = $this->authService->getIdentity();
        $pessoa = $this->pessoaTable->getPessoaPorEmail($email);
        $doador = $this->doadorTable->getDoadorPorPessoaId($pessoa->id);

        if ($doador) {
            $doacoes->doador_id = $doador->id;
        }
//        echo '<pre>'; print_r($doacoes); die;
        $salvou = $this->doacaoTable->saveDoacao($doacoes);

        if ($salvou) {
            $this->flashMessenger()->addMessage('Obrigado por sua doação, entraremos em contato com o recebedor para que este pegue a doação.');
            return $this->redirect()->toRoute('minha-conta');
        }
        return $this->redirect()->toRoute('minha-conta');
    }

    public function recebedorAction() {

        $email = $this->authService->getIdentity();
        $pessoa = $this->pessoaTable->getPessoaPorEmail($email);
        $recebedor = $this->recebedorTable->getRecebedorPorPessoaId($pessoa->id);
        $doacoes= $this->doacaoTable->getTodasDoacoesPorId($recebedor->id);
        $dados=[
            'doacoes'=>$doacoes
        ];
        
        $viewModel=new ViewModel($dados);
        
        return $viewModel;
    }
    public function editarAction () {
        
        $id = $this->params()->fromRoute("id", null);
        $form = new DoacaoForm();
        $form->get('submit')->setValue('Salvar');

        if ($id) {
            try {
            $doacao = $this->doacaoTable->getDoacao($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('minha-conta', ['action' => 'index']);
        }
        $form->bind($doacao);
//        echo '<pre>';        print_r($doacao); die;
        $viewData = ['id' => $id, 'form' => $form,'recibo'=>$doacao];
        return new ViewModel($viewData);
        }
         $viewData = ['id' => $id, 'form' => $form];
        return new ViewModel($viewData);
        
    }
    public function receberAction () {
        
         $email = $this->authService->getIdentity();
        $pessoa = $this->pessoaTable->getPessoaPorEmail($email);
        $recebedor = $this->recebedorTable->getRecebedorPorPessoaId($pessoa->id);

        $form = new DoacaoForm();
        $form->get('submit')->setValue('Salvar');

        $request = $this->getRequest();

        
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
//        echo '<pre>'; print_r($recebedor); die;
        $doacao->recebedor_id = $recebedor->id;
        $doacao->status = 0;
        $id=$this->doacaoTable->saveDoacao($doacao);
        
        if($id){
//            
            foreach ($doacao->itens as $item){
                $item->doacao_id=$id;
            }
//            echo '<pre>'; print_r($doacao); die;
            $this->itemTable->SalvarItemDoacao($doacao);
        }

        return $this->redirect()->toRoute('minha-conta');
    }

}
