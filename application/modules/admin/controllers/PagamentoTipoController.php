<?php

class Admin_PagamentoTipoController extends Zend_Controller_Action {

    private $_pagamentoTipoRepository;
    private $_empresa_id;

    public function init() {

        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {

        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addFilter('status != ?', PagamentoTipo::EXCLUIDO);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'id';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_pagamentoTipoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {


        if ($this->getRequest()->isPost()) {
            $pagamentoTipo = new PagamentoTipo();

            $data = $this->getRequest()->getPost();

            $this->_setData($pagamentoTipo, $data);

            $validate = $this->_validate($pagamentoTipo);

            if (!isset($validate) || $validate == "") {

                $pagamentoTipo->save();


                $this->_helper->FlashMessenger('Tipo de Pagamento cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/pagamento-tipo/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->pagamentoTipo = $pagamentoTipo;
            }
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $pagamentoTipo = $this->_pagamentoTipoRepository->getById($id);

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();
            $this->_setData($pagamentoTipo, $data);

            $validate = $this->_validate($pagamentoTipo);
            if (!isset($validate) || $validate == "") {
                $pagamentoTipo->save();

                $this->_helper->FlashMessenger('Tipo de Pagamento alterado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/pagamento-tipo/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->pagamentoTipo = $pagamentoTipo;
            }
        }
        $this->view->pagamentoTipo = $pagamentoTipo;
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $pagamentoTipo = $this->_pagamentoTipoRepository->getById($id);

        $pagamentoTipo->status = PagamentoTipo::EXCLUIDO;
        $pagamentoTipo->save();

        $this->_helper->FlashMessenger('TIpo de Pagamento removido com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/pagamento-tipo/');
    }

    private function _setData(PagamentoTipo $pagamentoTipo, $data) {

        $pagamentoTipo->descricao = $data['descricao'];
        $pagamentoTipo->status = $data['status'];
        $pagamentoTipo->percentual = AppUtil::convertStringToFloat($data['percentual']);
        $pagamentoTipo->empresa_id = $this->_empresa_id;

    }

    private function _validate(PagamentoTipo $pagamentoTipo) {
        if ($pagamentoTipo->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        $result .= $this->_validateDescricaoTipo($pagamentoTipo);

        return $result;
    }

    private function _validateDescricaoTipo(PagamentoTipo $pagamentoTipo) {
        if ($this->_pagamentoTipoRepository->isExiste($pagamentoTipo, $this->_empresa_id)) {
            $result .= "<li>Já existe um Tipo de Pagamento com essa <b>Descrição</b></li>";
        }

        return $result;
    }


}
