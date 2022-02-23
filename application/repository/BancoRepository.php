<?php

class BancoRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new Banco();
        parent::__construct();
    }

    public function getList($status = NULL) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->orderBy('descricao ASC');
        if ($status != NULL)
            $q->where("status = ?", array($status));
        return $q->execute();
    }

    public function getByDescricao($banco) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->where('p.descricao = ?', array($banco->descricao));
        if ($banco->id != NULL)
            $q->addWhere("p.id != ?", array($banco->id));
        return $q->fetchOne();
    }

}
