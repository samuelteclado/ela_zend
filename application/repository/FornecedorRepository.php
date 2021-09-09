<?php

class FornecedorRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new Fornecedor();
        parent::__construct();
    }

    public function getListByEmpresa($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere("status = " . Fornecedor::ATIVO)
            ->orderBy('descricao ASC');

        return $q->execute();
    }

    public function getListAll($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->orderBy('descricao ASC');

        return $q->execute();
    }

}
