<?php

class Ajax_PagamentoTipoController extends Zend_Controller_Action {

    private $_pagamentoTipoRepository;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
    }



    public function formaPagamentoAction() {

        $pagamento_tipo = $this->getRequest()->getParam('pagamentoTipo');


        $pagamento_tipo_obj = $this->_pagamentoTipoRepository->getById($pagamento_tipo);


        $pagamentoTipo = array();
        $pagamentoTipo['id'] = $pagamento_tipo_obj->id;
        $pagamentoTipo['percentual'] = AppUtil::convertFloatToString($pagamento_tipo_obj->percentual);


        $this->_helper->json(Zend_Json::encode($pagamentoTipo));
    }

}
