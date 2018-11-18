<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class RecebedorTable {

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
        $select = new \Zend\Db\Sql\Select(array('r' => 'recebedor'));
        $select->columns(array('*'));
        $select->join(array('pe' => 'pessoa'), 'r.pessoa_id = pe.id', array(
            'email' => 'email',
                ), 'LEFT');

        $select->join(array('pef' => 'pessoa_fisica'), 'pef.pessoa_id = pe.id', array(
            'nome' => 'nome',
            'cpf' => 'cpf',
            'rg' => 'rg'
                ), 'LEFT');
        $select->join(array('pej' => 'pessoa_juridica'), 'pej.pessoa_id = pe.id', array(
            'ie' => 'ie',
            'cnpj' => 'cnpj',
            'nome_fantasia' => 'nome_fantasia'
                ), 'LEFT');
        $select->join(array('end' => 'endereco'), 'pe.id = end.pessoa_id', array(
            'rua' => 'rua',
            'bairro' => 'bairro',
            'cidade' => 'cidade',
            'numero' => 'numero',
            'complemento' => 'complemento',
            'cep' => 'cep',
                ), 'LEFT');


        $adapterPaginator = new \Zend\Paginator\Adapter\DbSelect($select, $this->tableGateway->getAdapter());

        $paginator = new \Zend\Paginator\Paginator($adapterPaginator);

        return $paginator;
    }

    public function getRecebedor($id) {
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

    public function saveRecebedor(Recebedor $recebedor) {
        $data = $recebedor->getArrayCopy();

        $id = (int) $recebedor->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);

            return;
        }

        if (!$this->getRecebedor($id)) {
            throw new RuntimeException(sprintf(
                    'Cannot update usuario with identifier %d; does not exist', $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteRecebedor($id) {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

}
