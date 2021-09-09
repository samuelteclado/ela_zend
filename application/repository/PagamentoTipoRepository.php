<?php

class PagamentoTipoRepository extends RepositoryAbstract
{
    protected $_descriptionField = 'descricao';

    public function __construct() {
        $this->obj = new PagamentoTipo();
        parent::__construct();
    }

    public function isExiste($pagamentoTipo, $empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.descricao = ?", array($pagamentoTipo->descricao))
            ->addWhere("p.status != ?", array(PagamentoTipo::EXCLUIDO))
            ->addWhere("p.empresa_id = ?", array($empresa));

        if ($pagamentoTipo->id > 0)
            $q->addWhere("p.id != ?", array($pagamentoTipo->id));
        return $q->count() > 0;
    }

    public function getListByEmpresa($empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa))
            ->addWhere("status = ". PagamentoTipo::ATIVO)
            ->orderBy('descricao ASC');
        return $q->execute();
    }
}