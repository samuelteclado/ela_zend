<?php

class Ajax_ProcedimentoTipoController extends Zend_Controller_Action {

    private $_procedimentoTipoRepository;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_procedimentoTipoRepository = new ProcedimentoTipoRepository();
    }



    public function procedimentoAction() {

        $procedimento_id = $this->getRequest()->getParam('procedimento');

        $procedimento_obj = $this->_procedimentoTipoRepository->getById($procedimento_id);


        $procedimento = array();
        $procedimento['id'] = $procedimento_obj->id;
        $procedimento['descricao'] = $procedimento_obj->descricao;
        $procedimento['valor'] = AppUtil::convertFloatToString($procedimento_obj->valor);

        $this->_helper->json(Zend_Json::encode($procedimento));
    }



}
