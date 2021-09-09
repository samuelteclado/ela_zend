<?php

class Ajax_FornecedorCategoriaController extends Zend_Controller_Action {

    private $_fornecedorCategoriaRepository;
    private $_empresa_id;

    public function init() {
        $this->_fornecedorCategoriaRepository = new FornecedorCategoriaRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }
    
    public function listAction() {
        $categorias = $this->_fornecedorCategoriaRepository->getList($this->_empresa_id);
        
        $categoria = array();
        $categoria_list = array();

        foreach ($categorias as $categoria_obj):

            $categoria['id'] = $categoria_obj->id;
            $categoria['descricao'] = $categoria_obj->descricao;
            

            $categoria_list[] = $categoria;

        endforeach;

        $this->_helper->json(Zend_Json::encode($categoria_list));
    }


}