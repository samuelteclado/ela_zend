<?php

class Admin_ContaBancariaController extends Zend_Controller_Action {

    private $_bancoRepository;
    private $_contaBancariaRepository;
    private $_empresa_id;

    public function init() {
        $this->_bancoRepository = new BancoRepository();
        $this->_contaBancariaRepository = new ContaBancariaRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addJoinFilter('p.Banco b');


        $sortParam = ($params["sort"]) ? $params["sort"] : 'id';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_contaBancariaRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $conta_bancaria = new ContaBancaria();

            $data = $this->getRequest()->getPost();

            $this->_setData($conta_bancaria, $data);

            $validate = $this->_validate($conta_bancaria);

            if (!isset($validate) || $validate == "") {

                $conta_bancaria->save();

                $this->_helper->FlashMessenger('Conta Bancaria cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/conta-bancaria/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->conta_bancaria = $conta_bancaria;
                $this->view->bancos = $this->_bancoRepository->getList(Banco::ATIVO);
            }
        } else {
            $this->view->bancos = $this->_bancoRepository->getList(Banco::ATIVO);
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $conta_bancaria = $this->_contaBancariaRepository->getById($id);

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();
            $this->_setData($conta_bancaria, $data);

            $validate = $this->_validate($conta_bancaria);
            if (!isset($validate) || $validate == "") {
                $conta_bancaria->save();

                $this->_helper->FlashMessenger('Conta alterada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/conta-bancaria/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->conta_bancaria = $conta_bancaria;
                $this->view->bancos = $this->_bancoRepository->getList(Banco::ATIVO);
            }
        } else {
            $this->view->conta_bancaria = $conta_bancaria;
            $this->view->bancos = $this->_bancoRepository->getList(Banco::ATIVO);
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $conta = $this->_contaBancariaRepository->getById($id);
        $conta->status = ContaBancaria::EXCLUIDO;
        $conta->save();

        $this->_helper->FlashMessenger('Conta removida com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/conta-bancaria/');
    }

    private function _setData(ContaBancaria $conta_bancaria, $data) {
        $conta_bancaria->descricao = $data['descricao'];
        $conta_bancaria->agencia = $data['agencia'];
        $conta_bancaria->conta = $data['conta'];
        $conta_bancaria->banco_id = $data['banco'];
        $conta_bancaria->status = $data['status'];
        $conta_bancaria->pix = $data['pix'];
        $conta_bancaria->empresa_id = $this->_empresa_id;

    }


    private function _validate(ContaBancaria $conta_bancaria) {
        if ($conta_bancaria->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        if ($conta_bancaria->banco_id <= 0)
            $result .= "<li>O campo <b>Banco</b> deve ser informado.</li>";

        if ($conta_bancaria->conta == "")
            $result .= "<li>O campo <b>Conta</b> deve ser informado.</li>";

        if ($conta_bancaria->agencia == "")
            $result .= "<li>O campo <b>Agência</b> deve ser informado.</li>";

        $result .= $this->_validateContaExistete($conta_bancaria);

        return $result;
    }

    private function _validateContaExistete(ContaBancaria $conta_bancaria) {
        $conta_db = $this->_contaBancariaRepository->getByConta($conta_bancaria, $this->_empresa_id);

        if ($conta_db->id > 0)
            return $result .= "<li>Já existe uma <b>Conta</b> com este mesmo número nesta agncia.</li>";

        return "";
    }

}
