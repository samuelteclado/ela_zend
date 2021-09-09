<?php

class FornecedorCategoriaRepository extends RepositoryAbstract {

    protected $_descriptionField = 'descricao';

    public function __construct() {
        $this->obj = new FornecedorCategoria();
        parent::__construct();
    }

    public function getList($empresa_id) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where('empresa_id = ?', array($empresa_id))
                ->orderBy('descricao ASC');
        return $q->execute();
    }

    public function getByDescricao($descricao, $empresa_id) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->where("p.descricao = ?", array($descricao))
                ->addWhere("p.empresa_id = ?", array($empresa_id));
        return $q->fetchOne();
    }

}
