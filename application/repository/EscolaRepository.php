<?php

class EscolaRepository extends RepositoryAbstract {

    protected $_descriptionField = 'cnpj';

    public function __construct() {
        $this->obj = new Escola();
        parent::__construct();
    }
    
    public function getById($id, $escola_id = NULL) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("id = ?", array($id));
        if ($escola_id != NULL)
            $q->addWhere("id = ?", array($escola_id));
        return $q->fetchOne();
    }
  

    public function getByCNPJ($cnpj) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->where("cnpj = ?", array($cnpj));
        return $q->fetchOne();
    }
    
    public function getCodigoByEscola($escola_id) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj) . ' p')
                ->select('p.referencia_biblioteca')
                ->where("p.id = ?", array($escola_id));
        return $q->fetchOne()->referencia_biblioteca;
    }
    
    public function getActivesAll() {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("status = ?", array(Escola::ATIVA))
                ->orderBy('nome_fantasia ASC');
        return $q->execute();
    }

    public function getEscolaSession($escola_id) {
         $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("id = ?", array($escola_id));
        return $q->fetchOne();
    }

}
