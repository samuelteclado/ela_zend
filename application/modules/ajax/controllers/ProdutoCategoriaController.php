<?php

class Ajax_ProdutoCategoriaController extends Zend_Controller_Action {

    private $_produtoCategoriaRepositoy;
    private $_empresa_id;


    public function init() {
        $this->_helper->layout->disableLayout();

        $this->_produtoCategoriaRepositoy = new ProdutoCategoriaRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }


    public function listAction() {
        $categoria_produtos = $this->_produtoCategoriaRepositoy->getList($this->_empresa_id);

        $categoria = array();
        $categoria_list = array();

        foreach ($categoria_produtos as $categoria_obj):

            $categoria['id'] = $categoria_obj->id;
            $categoria['descricao'] = $categoria_obj->descricao;

            $categoria_list[] = $categoria;

        endforeach;

        $this->_helper->json(Zend_Json::encode($categoria_list));
    }


}
