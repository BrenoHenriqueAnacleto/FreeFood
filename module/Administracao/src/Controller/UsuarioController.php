<?php

namespace Administracao\Controller;

use Administracao\Form\UsuarioForm;
use Administracao\Model\Usuario;
use Administracao\Model\UsuarioTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsuarioController extends AbstractActionController
{
    private $table;

    public function __construct(UsuarioTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        // Grab the paginator from the UsuarioTable:
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
        $form = new UsuarioForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $album = new Usuario();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveUsuario($album);

        return $this->redirect()->toRoute('usuario');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('usuario', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->table->getUsuario($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('usuario', ['action' => 'index']);
        }

        $form = new UsuarioForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request  = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveUsuario($album);

        // Redirect to album list
        return $this->redirect()->toRoute('usuario', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('usuario');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteUsuario($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('usuario');
        }

        return [
            'id'    => $id,
            'album' => $this->table->getUsuario($id),
        ];
    }
}
