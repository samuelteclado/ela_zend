<?php

class Ajax_BuscaCepController extends Zend_Controller_Action {

    public function buscaCepAction() {
        $cep = $this->getRequest()->getParam('cep');
        $endereco = AppUtil::busca_cep($cep);
        $this->_helper->json(Zend_Json::encode($endereco));
    }

}
