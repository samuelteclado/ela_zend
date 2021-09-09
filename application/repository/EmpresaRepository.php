<?php

class EmpresaRepository extends RepositoryAbstract
{

    protected $_descriptionField = 'cnpj';

    public function __construct() {
        $this->obj = new Empresa();
        parent::__construct();
    }


    public function getById($id, $empresa_id = NULL) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("id = ?", array($id));
        if ($empresa_id != NULL)
            $q->addWhere("id = ?", array($empresa_id));
        return $q->fetchOne();
    }

    public function getByCNPJ($cnpj) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj) . ' p')
            ->where("cnpj = ?", array($cnpj));
        return $q->fetchOne();
    }

    public function getEmpresaSession($empresa_id) {
        $q = Doctrine_Query::create()
            ->from(get_class($this->obj))
            ->where("id = ?", array($empresa_id));
        return $q->fetchOne();
    }
}