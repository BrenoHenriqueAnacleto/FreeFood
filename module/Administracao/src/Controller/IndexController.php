<?php

namespace Administracao\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationServiceInterface;

class IndexController extends AbstractActionController
{
    public $authService;
    public $pessoaTable;
    public $administradorTable;

    public function __construct(AuthenticationServiceInterface $authService, \Administracao\Model\PessoaTable $pessoaTable, \Administracao\Model\AdministradorTable $administradorTable) {

        $this->administradorTable = $administradorTable;
        $this->pessoaTable = $pessoaTable;
        $this->authService = $authService;
    }

    public function indexAction() {
        if ($this->authService->hasIdentity()) {

            $email = $this->authService->getIdentity();
            $pessoa = $this->pessoaTable->getPessoaPorEmail($email);
            $administrador = $this->administradorTable->getAdministradorPorPessoaId($pessoa->id);
        }
        if ($administrador) {
            return new ViewModel();
        }
        
        return $this->redirect()->toRoute('home');
           
    }

}
