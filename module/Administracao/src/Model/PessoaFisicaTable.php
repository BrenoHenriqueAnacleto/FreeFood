<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PessoaFisicaTable {

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
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the PessoaFisica entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new PessoaFisica());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
                // our configured select object:
                $select,
                // the adapter to run it against:
                $this->tableGateway->getAdapter(),
                // the result set to hydrate:
                $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);

        return $paginator;
    }

    public function getPessoaFisica($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['pessoa_id' => $id]);
        $row = $rowset->current();
        if (!$row) {
            return null;
        }

        return $row;
    }

    public function savePessoaFisica(PessoaFisica $pessoaFisica) {

        $data = $pessoaFisica->getArrayCopy();
//        echo '<pre>'; print_r($pessoaFisica); die;
        $id = (int) $pessoaFisica->pessoa_id;

        if ($this->getPessoaFisica($id)) {
            
            $this->tableGateway->update($data, ['pessoa_id' => $id]);
            return $id;
            
        } else {
            
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
            return $id;
        }
        return null;
    }

    public function deletePessoaFisica($id) {
        $this->tableGateway->delete(['pessoa_id' => (int) $id]);
    }

}
