<?php

namespace Administracao\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Administracao\Model\Doacao;
class DoacaoTable {

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
        $select = new \Zend\Db\Sql\Select(array('do' => 'doacao'));
        $select->columns(array('*'));

        $adapterPaginator = new \Zend\Paginator\Adapter\DbSelect($select, $this->tableGateway->getAdapter());

        $paginator = new \Zend\Paginator\Paginator($adapterPaginator);

        return $paginator;
    }
    
      public function buscaTodos() {
        // Create a new Select object for the table:
        $select = new \Zend\Db\Sql\Select(array('do' => 'doacao'));
        $select->columns(array('*'));
        $select->where('status = 0');
        $adapterPaginator = new \Zend\Paginator\Adapter\DbSelect($select, $this->tableGateway->getAdapter());

        $paginator = new \Zend\Paginator\Paginator($adapterPaginator);
        error_log($select->getSqlString());
        return $paginator;
    }
    
    public function getTodos($paginated = false){
        
        if ($paginated) {
            error_log('teste');
            return $this->buscaTodos();
        }
        return $this->tableGateway->select('status = 0');
    }
     public function getTodasDoacoesPorId($id){
         
        return $this->tableGateway->select('recebedor_id =' . $id.' AND status = 0');
    }

    public function getDoacao($id) {

        
        $adapter = $this->tableGateway->getAdapter();
        
        $sql = new \Zend\Db\Sql\Sql($adapter);
              
        $dados_item = array(
                                'nome'           => 'nome',
                                'descricao'       => 'descricao',
                                'quantidade'      => 'quantidade',
                                'valor_unitario'  => 'valor_unitario',
                                'doacao_id'       => 'doacao_id',
                               );
        
        
        $select = new Select();
        
        $select->from(array('do' => $this->tableGateway->getTable()),'*','LEFT');
        
        $select->join(array('it'  => 'item'), 'do.id = it.doacao_id',$dados_item, 'LEFT');
               
        $select->where(array(
           'do.id' => $id,
        ));
        
        
        //gera a string SQL
        $selectString = $sql->getSqlStringForSqlObject($select); 
        
        // Executa a consulta
        $resultado = $adapter->query($selectString, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
        
        if($resultado->Count() > 0 ) {
            return $this->extrairItem($resultado);
            //return $rowset->Current();
        }
        
        return NULL;
       
    }

    public function saveDoacao(Doacao $doador) {

        $data = $doador->getArrayCopySingle();
        $id = (int) $doador->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
            return $id;
        }

        if (!$this->getDoacao($id)) {
            throw new RuntimeException(sprintf(
                    'Cannot update usuario with identifier %d; does not exist', $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
        return $id;
    }

    public function deleteDoacao($id) {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
     private function extrairItem($dados){
       
        $doacao = new \Administracao\Model\Doacao();
        //$doacao = new \Application\Model\Pessoa();
        
        $r = $dados->Current();

        $doacao->id            = $r['id'];
        $doacao->titulo        = $r['titulo'];
        $doacao->descricao     = $r['descricao'];
        $doacao->recebedor_id  = $r['recebedor_id'];
        $doacao->doador_id     = $r['doador_id'];

        
        $doacao->itens = array();
        
        for ($count = 0; $count < $dados->Count(); $count++) {          
            
            $item         = new \Administracao\Model\Item();

            $item->id               = $r['id'];
            $item->nome             = $r['nome'];
            $item->descricao        = $r['descricao'];
            $item->quantidade       = $r['quantidade'];
            $item->valor_unitario   = $r['valor_unitario'];
            $item->doacao_id        = $r['doacao_id'];
            
            $doacao->itens[]        = $item;
            
            $dados->next();
            $r = $dados->Current();
        }
         
        // remove possiveis itens repetidos
        $doacao->itens = array_map("unserialize", array_unique(array_map("serialize",$doacao->itens)));
        return $doacao;
    }
}
