<?php

class UsuarioGrupoPermissaoFuncionalidadeRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new UsuarioGrupoPermissaoFuncionalidade();
        parent::__construct();
    }
    
     public function getList() {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->orderBy('nome ASC');
        return $q->execute();
    }

}
