<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class EnderecoTable
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
 public function save(\Administracao\Model\Endereco $registro)
    {
        $salvou = false;
        
        $id = (int) (!is_null($registro->getPrimaryKey()))? $registro->getPrimaryKey(): 0;
        if ($id == 0) {

            $salvou = (bool)$this->tableGateway->insert($registro->getArrayCopy());
        } else {

            $salvou = (bool)$this->tableGateway->update($registro->getArrayCopy(), array($this->primaryKey => $id));
        }
                
        if($salvou){
            
            $id = ($id > 0)? $id : $this->tableGateway->lastInsertValue;
            
            $registro->SetPrimaryKeyValue($id);
            
            return $id;
        }
        else{
            
            return FALSE;
        }
    }
    
    public function getEndereco($id)
    {
        $id     = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row    = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveEndereco(Endereco $endereco)
    {
        
        $data=$endereco->getArrayCopy();
//        echo '<pre>'; print_r($data); die;
        $id = (int) $data->id;

        if ($id === 0) {
//            echo '<pre>'; print_r($data); die;
            $this->tableGateway->insert($data);

            return $this->tableGateway->lastInsertValue;
        }

        if (!$this->getEndereco($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update usuario with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteEndereco($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
      public function SalvarEndereco(\Administracao\Model\Pessoa $pessoa) {

        try {
            
            $where = new \Zend\Db\Sql\Where();
            
            $where->equalTo('pessoa_id', $pessoa->id);
            
            $this->tableGateway->delete($where);

            foreach ($pessoa->endereco as $arre) {
                
                if (!empty($arre)) {
                    if(!is_array($arre)){
                    $arre->id=0;
//                    error_log(json_encode($arre));
//                    echo '<pre>'; print_r($arre); die;
                    $this->saveEndereco($arre);
                    }
                }
            }
            
            return TRUE;
            
        } catch (Exception $e) {
            
            return FALSE;
        }
    }

}
