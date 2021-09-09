<?php

class Ajax_FornecedorContatoController extends Zend_Controller_Action {

    private $_fornecedorContatoRepository;
    private $_escola;

    public function init() {
        $this->_fornecedorContatoRepository = new FornecedorContatoRepository();
        $this->_escola = SessionUtil::getEscolaSession();
    }

    public function deletarAction() {
        $contato_id = $this->getRequest()->getParam('contato_id');

        $contato = $this->_fornecedorContatoRepository->getById($contato_id, $this->_escola);

        $contato->delete();

        $messenger['success'] = TRUE;
        $this->_helper->json(Zend_Json::encode($messenger));
    }

}
