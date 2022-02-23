<?php

class MovimentoItemRepository extends RepositoryAbstract {

    public function __construct() {
        $this->obj = new MovimentoItem();
        parent::__construct();
    }



}
