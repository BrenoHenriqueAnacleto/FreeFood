<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class AdministradorTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    private function fetchPaginatedResults() {
        $select = new \Zend\Db\Sql\Select(array('r' => 'administrador'));
        $select->columns(array('*'));
        $select->join(array('pe' => 'pessoa'), 'r.pessoa_id = pe.id', array(
            'email' => 'email',
                ), 'LEFT');
        
        $adapterPaginator = new \Zend\Paginator\Adapter\DbSelect($select, $this->tableGateway->getAdapter());

        $paginator = new \Zend\Paginator\Paginator($adapterPaginator);

        return $paginator;
    }

    public function getAdministrador($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                    'Could not find row with identifier %d', $id
            ));
        }

        return $row;
    }
    
    public function getAdministradorPorPessoaId($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['pessoa_id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            return false;
        }

        return $row;
    }

    public function saveAdministrador(Administrador $administrador) {
        $data = $administrador->getArrayCopy();

        $id = (int) $administrador->id;
        error_log("ollllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllaaaa");
        if ($id === 0) {
            error_log("ollllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllllaaaa2");
            $this->tableGateway->insert($data);

            return;
        }

        if (!$this->getAdministrador($id)) {
            throw new RuntimeException(sprintf(
                    'Cannot update usuario with identifier %d; does not exist', $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAdministrador($id) {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
  public function getAdministradores() {
        $adapter = $this->tableGateway->getAdapter();

        $sql = new \Zend\Db\Sql\Sql($adapter);

        $select = new \Zend\Db\Sql\Select(array('d' => 'administrador'));
        $select->columns(array('*'));
        $select->join(array('pe' => 'pessoa'), 'd.pessoa_id = pe.id', array(
            'email' => 'email',
                ), 'LEFT');

        $select->group('id');

        $resultado = $this->tableGateway->selectWith($select)->toArray();

        $administradores = array();

        foreach ($resultado as $r) {

            $administradores[$r['id']] = $r['id'];
        }

        return $administradores;
    }
}
