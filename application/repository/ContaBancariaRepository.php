<?php

class ContaBancariaRepository extends RepositoryAbstract {

    protected $_descriptionField = 'descricao';

    public function __construct() {
        $this->obj = new ContaBancaria();
        parent::__construct();
    }


    public function getFistConta($empresa_id = NULL) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("status = " . ContaBancaria::ATIVO)
            ->orderBy('id ASC');
        return $q->fetchOne();
    }


    public function getListActive($empresa_id = NULL) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("status = " . ContaBancaria::ATIVO);
        if ($empresa_id != NULL)
            $q->addWhere("empresa_id = ?", array($empresa_id));
        else $q->addWhere("empresa_id IS NULL");
        return $q->execute();
    }


    public function getListById($empresa_id,$id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.empresa_id = ?", array($empresa_id));
        if ($id > 0) {
            $q->addWhere('id = ?', $id);
        }

        return $q->execute();
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

    public function getByConta($conta_bancaria, $empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.banco_id = ?", array($conta_bancaria->banco_id))
            ->addWhere("p.agencia = ?", array($conta_bancaria->agencia))
            ->addWhere("p.conta = ?", array($conta_bancaria->conta))
            ->addWhere("p.empresa_id = ?", array($empresa));
        if ($conta_bancaria->id != NULL)
            $q->addWhere("p.id != ?", array($conta_bancaria->id));
        return $q->fetchOne();
    }

}
