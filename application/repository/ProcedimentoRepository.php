<?php

class ProcedimentoRepository extends RepositoryAbstract
{

    public function __construct() {
        $this->obj = new Procedimento();
        parent::__construct();
    }

    public function getProcedimentoByCliente($cliente) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('cliente_id = ?', array($cliente))
            ->orderBy('data ASC');
        return $q->execute();
    }
}