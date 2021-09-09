<?php

class LancamentoRecorrenciaRepository extends RepositoryAbstract {


    public function __construct() {
        $this->obj = new LancamentoRecorrencia();
        parent::__construct();
    }
    
}
