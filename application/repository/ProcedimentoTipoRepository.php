<?php

class ProcedimentoTipoRepository extends RepositoryAbstract
{

    protected $_descriptionField = 'descricao';

    public function __construct() {
        $this->obj = new ProcedimentoTipo();
        parent::__construct();
    }

    public function isExiste($procedimentoTipo, $empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.descricao = ?", array($procedimentoTipo->descricao))
            ->addWhere("p.status != ?", array(ProcedimentoTipo::EXCLUIDO))
            ->addWhere("p.tipo = ?", array($procedimentoTipo->tipo))
            ->addWhere("p.empresa_id = ?", array($empresa));

        if ($procedimentoTipo->id > 0)
             $q->addWhere("p.id != ?", array($procedimentoTipo->id));
       // echo $q->getSqlQuery();
        //die;
        return $q->count() > 0;
    }

    public function getListByEmpresa($empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa))
            ->addWhere("status = ". ProcedimentoTipo::ATIVO)
            ->orderBy('descricao ASC');
        return $q->execute();
    }


    public function getByDescricao($empresa,$descricao) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.empresa_id = ?", array($empresa))
            ->addWhere("p.descricao = ?", array($descricao));
        return $q->fetchOne();
    }

}