<?php

class ProdutoRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new Produto();
        parent::__construct();
    }

    public function getListByEmpresa($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere("status = " . Produto::ATIVO)
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


    public function getTotalProdutos($empresa_id) {
        $q = Doctrine_Query::create()
            ->addSelect('(COUNT(id)) AS total ')
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id));
        return $q->fetchOne()->total;
    }

    public function getTotalProdutosByCategoria($categoria, $empresa_id) {
        $q = Doctrine_Query::create()
            ->addSelect('(COUNT(id)) AS total ')
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->addWhere('produto_categoria_id = ?', array($categoria));
        return $q->fetchOne()->total;
    }


    public function isExiste($produto, $empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.descricao = ?", array($produto->descricao))
            ->addWhere("p.status != ?", array(Produto::EXCLUIDO))
            ->addWhere("p.empresa_id = ?", array($empresa));

        if ($produto->id > 0)
            $q->addWhere("p.id != ?", array($produto->id));

        return $q->count() > 0;
    }

}
