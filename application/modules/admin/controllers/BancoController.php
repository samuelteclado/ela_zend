<?php

class Admin_BancoController extends Zend_Controller_Action {

    private $_bancorepository;

    public function init() {
        $this->_bancorepository = new BancoRepository();
    }

    public function indexAction() {

        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addSelectFilter('status', $params['status']);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'id';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_bancorepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $banco = new Banco();

            $data = $this->getRequest()->getPost();

            $this->_setData($banco, $data);

            $validate = $this->_validate($banco);

            if (!isset($validate) || $validate == "") {

                $banco->save();


                $this->_helper->FlashMessenger('Banco cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/banco/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->banco = $banco;
            }
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $banco = $this->_bancorepository->getById($id);

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();
            $this->_setData($banco, $data);

            $validate = $this->_validate($banco);
            if (!isset($validate) || $validate == "") {
                $banco->save();

                $this->_helper->FlashMessenger('Banco alterado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/banco/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->banco = $banco;
            }
        } else {
            $this->view->banco = $banco;
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');
        $this->_bancorepository->remove($id);
        $this->_helper->FlashMessenger('Banco removido com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/banco/');
    }

    private function _setData(Banco $banco, $data) {
        $banco->descricao = $data['descricao'];
        $banco->codigo = $data['codigo'];
        $banco->status = $data['status'];
    }

    private function _validate(Banco $banco) {
        if ($banco->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        $result .= $this->_validateBancoExistente($banco);

        return $result;
    }

    private function _validateBancoExistente(Banco $banco) {
        $banco = $this->_bancorepository->getByDescricao($banco);

        if ($banco->id > 0)
            return $result .= "<li>Já existe um banco cadastrado com esta descrição.</li>";

        return "";
    }

}
