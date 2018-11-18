<?php

namespace Administracao\Controller;

use Administracao\Form\PessoaForm;
use Administracao\Model\Pessoa;
use Administracao\Model\PessoaTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PessoaController extends AbstractActionController
{
    private $table;

    public function __construct(PessoaTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        // Grab the paginator from the PessoaTable:
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
        $form = new PessoaForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $pessoa = new Pessoa();
        $form->setInputFilter($pessoa->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $pessoa->exchangeArray($form->getData());
        $this->table->savePessoa($pessoa);

        return $this->redirect()->toRoute('pessoa');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'add']);
        }

        // Retrieve the pessoa with the specified id. Doing so raises
        // an exception if the pessoa is not found, which should result
        // in redirecting to the landing page.
        try {
            $pessoa = $this->table->getPessoa($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('pessoa', ['action' => 'index']);
        }

        $form = new PessoaForm();
        $form->bind($pessoa);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($pessoa->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->savePessoa($pessoa);

        // Redirect to pessoa list
        return $this->redirect()->toRoute('pessoa', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('pessoa');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deletePessoa($id);
            }

            // Redirect to list of pessoas
            return $this->redirect()->toRoute('pessoa');
        }

        return [
            'id'    => $id,
            'pessoa' => $this->table->getPessoa($id),
        ];
    }
}
