<?php

class Ajax_ClienteController extends Zend_Controller_Action {

    private $_clienteRepository;
    private $_empresa_id;

    public function init() {
        $this->_clienteRepository = new ClienteRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function listAction() {
        $clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);

        $cliente = array();
        $cliente_list = array();

        foreach ($clientes as $cliente_obj):

            $cliente['id'] = $cliente_obj->id;
            $cliente['nome'] = $cliente_obj->nome .' '. $cliente_obj->sobrenome;

            $cliente_list[] = $cliente;

        endforeach;

        $this->_helper->json(Zend_Json::encode($cliente_list));
    }


}