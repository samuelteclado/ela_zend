<?php

class RegistroPontoRepository extends RepositoryAbstract
{

    public function __construct() {
        $this->obj = new RegistroPonto();
        parent::__construct();
    }

}