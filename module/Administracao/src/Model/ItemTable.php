<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class ItemTable {

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

        // Create a new result set based on the Endereco entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Endereco());

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

    public function getItem($id) {

        $adapter = $this->tableGateway->getAdapter();

        $sql = new \Zend\Db\Sql\Sql($adapter);

        $id = (int) $id;
        $where = new \Zend\Db\Sql\Where();
        $select = new \Zend\Db\Sql\Select(array('d' => 'doador'));
        $select->columns(array('*'));
        $select->join(array('pe' => 'pessoa'), 'd.pessoa_id = pe.id', array(
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
        $where->equalTo('d.id', $id);
        $select->where($where);

        $selectString = $sql->getSqlStringForSqlObject($select);

        // Executa a consulta
        $resultado = $adapter->query($selectString, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

        if ($resultado->Count() > 0) {

            return $resultado->Current();
            //return $rowset->Current();
        }

        return NULL;
    }

    public function saveItem(Item $doador) {

        $data = $doador->getArrayCopy();
//echo '<pre>';print_r($doador); die;
        $id = (int) $doador->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);

            return;
        }

        if (!$this->getItem($id)) {
            throw new RuntimeException(sprintf(
                    'Cannot update usuario with identifier %d; does not exist', $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteItem($id) {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

    public function SalvarItemDoacao(\Administracao\Model\Doacao $doacao) {
        try {

            $where = new \Zend\Db\Sql\Where();

            $where->equalTo('doacao_id', $doacao->id);

            $this->tableGateway->delete($where);
//            echo '<pre>'; print_r($doacao->itens); die;
            foreach ($doacao->itens as $item) {
                error_log(json_encode($item));
                if (!empty($item)) {

                    $item->id = 0;

                    $this->saveItem($item);
                }
            }

            return TRUE;
        } catch (Exception $e) {

            return FALSE;
        }
    }

}
