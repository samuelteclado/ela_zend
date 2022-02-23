<?php

class Ajax_MovimentoItemController extends Zend_Controller_Action {

    private $_movimentoItemRepository;

    public function init() {
        $this->_movimentoItemRepository = new MovimentoItemRepository();
    }

    public function deletarAction() {
        $produto_id = $this->getRequest()->getParam('produto_id');

        $produto_item = $this->_movimentoItemRepository->getById($produto_id);
        $produto_item->delete();
        $messenger['success'] = TRUE;
        $this->_helper->json(Zend_Json::encode($messenger));
    }

}
