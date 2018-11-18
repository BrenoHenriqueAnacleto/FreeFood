<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PessoaJuridicaTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false)
    {
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }

        return $this->tableGateway->select();
    }

    private function fetchPaginatedResults()
    {
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the PessoaJuridica entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new PessoaJuridica());

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

    public function getPessoaJuridica($id)
    {
        $id     = (int) $id;
        $rowset = $this->tableGateway->select(['usuario_id' => $id]);
        $row    = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function savePessoaJuridica(PessoaJuridica $pessoaJuridica)
    {
       
        $data=$pessoaJuridica->getArrayCopySingle();
//echo '<pre>'; print_r($data); die;
        $id = (int) $pessoaJuridica->pessoa_id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
            return $id;
        }

        if (!$this->getPessoaJuridica($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update usuario with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['pessoa_id' => $id]);
        return $id;
    }

    public function deletePessoaJuridica($id)
    {
        $this->tableGateway->delete(['pessoa_id' => (int) $id]);
    }
}
