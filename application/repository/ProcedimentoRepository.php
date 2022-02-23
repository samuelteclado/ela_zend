<?php

class ProcedimentoRepository extends RepositoryAbstract
{

    public function __construct() {
        $this->obj = new Procedimento();
        parent::__construct();
    }

    public function getProcedimentoByCliente($cliente,$limite ) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('cliente_id = ?', array($cliente))
            //->addWhere('status = '. Procedimento::ABERTO. ' OR status = ' . Procedimento::REALIZADO)
            ->orderBy('data DESC');
            if ($limite != NULL)
                 $q->limit(24);


       // echo $q->getSqlQuery();
       // die;
        return $q->execute();
    }


}