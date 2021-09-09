<?php

class ClienteRepository extends RepositoryAbstract
{

    protected $_descriptionField = 'cpf';

    public function __construct() {
        $this->obj = new Cliente();
        parent::__construct();
    }

    public function getListActive($empresa_id, $ids = array(), $selected_id = 0) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->orderBy('nome ASC');

        if ($selected_id <= 0)
            $q->addWhere('status = ' . Cliente::ATIVO);
        else
            $q->addWhere('status = ' . Cliente::ATIVO . ' OR id = ' . $selected_id);

        if (count($ids) > 0)
            $q->andWhereIn("id", $ids);

        return $q->execute();
    }

    public function getUltimoAtendimento() {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->leftJoin('p.Procedimento pr')
            ->where('pr.status = ' . Procedimento::REALIZADO)
            ->orderBy('pr.data DESC');
        echo $q->getSqlQuery();
        die;
        return $q->fetchOne()->data;
    }

    public function isExiste($cliente, $empresa) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("p.email = ?", array($cliente->email))
            ->addWhere("p.status != ?", array(Cliente::EXCLUIDO))
            ->addWhere("p.empresa_id = ?", array($empresa));

        if ($cliente->id > 0)
            $q->addWhere("p.id != ?", array($cliente->id));

        return $q->count() > 0;
    }

    public function getListAll($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where('empresa_id = ?', array($empresa_id))
            ->orderBy('nome ASC');

        return $q->execute();
    }

    public function getListByEmpresa($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("empresa_id = ?", array($empresa_id))
            ->addWhere("status = " . Cliente::ATIVO)
            ->orderBy('nome ASC');

        return $q->execute();
    }

}