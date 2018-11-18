<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class DoadorTable {

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

    public function getDoador($id) {
        
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
        $select->join(array('end' => 'endereco'), 'pe.id = end.pessoa_id', array(
            'rua' => 'rua',
            'bairro' => 'bairro',
            'cidade' => 'cidade',
            'numero' => 'numero',
            'complemento' => 'complemento',
            'cep' => 'cep',
                ), 'LEFT');
        $where->equalTo('d.id', $id);
        $select->where($where);

         $selectString = $sql->getSqlStringForSqlObject($select); 
        
        // Executa a consulta
        $resultado = $adapter->query($selectString, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        
        if($resultado->Count() > 0 ) {
            
            return $resultado->Current();
            //return $rowset->Current();
        }
        
        return NULL;
    }
    

    public function saveDoador(Doador $doador) {
        $data = $doador->getArrayCopy();

        $id = (int) $doador->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);

            return;
        }

        if (!$this->getDoador($id)) {
            throw new RuntimeException(sprintf(
                    'Cannot update usuario with identifier %d; does not exist', $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteDoador($id) {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

}
