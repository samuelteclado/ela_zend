<?php

class UsuarioGrupoRepository extends RepositoryAbstract {

    protected  $_descriptionField = 'nome';

    public function  __construct() {
        $this->obj = new UsuarioGrupo();
        parent::__construct();
    }

     public function getListGroup() {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where("id != ?", array(UsuarioGrupo::ADMINISTRADOR))
                ->orderBy('descricao ASC');
        return $q->execute();
    }
}