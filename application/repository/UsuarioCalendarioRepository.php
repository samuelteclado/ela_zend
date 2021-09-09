<?php

class UsuarioCalendarioRepository extends RepositoryAbstract
{

    public function __construct() {
        $this->obj = new UsuarioCalendario();
        parent::__construct();
    }


}