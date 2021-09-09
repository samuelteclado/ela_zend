<?php

class LancamentoRecorrenciaTipoRepository extends RepositoryAbstract {


    public function __construct() {
        $this->obj = new LancamentoRecorrenciaTipo();
        parent::__construct();
    }
    
  public function getListActive() {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("status = ". LancamentoRecorrenciaTipo::ATIVO);
        return $q->execute();
    }
    
}
