<?php

class Admin_ProcedimentoTipoController extends Zend_Controller_Action {

    private $_procedimentoTipoRepository;
    private $_empresa_id;

    public function init() {

        $this->_procedimentoTipoRepository = new ProcedimentoTipoRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {

        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addSelectFilter('tipo', $params['tipo']);
        $filter->addSelectFilter('procedimento_categoria', $params['categoria']);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addFilter('status != ?', ProcedimentoTipo::EXCLUIDO);



        $sortParam = ($params["sort"]) ? $params["sort"] : 'descricao';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_procedimentoTipoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(20);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {
        if ($this->getRequest()->isPost()) {
            $procedimentoTipo = new ProcedimentoTipo();

            $data = $this->getRequest()->getPost();

            $this->_setData($procedimentoTipo, $data);

            $validate = $this->_validate($procedimentoTipo);

            if (!isset($validate) || $validate == "") {

                $procedimentoTipo->save();


                $this->_helper->FlashMessenger('Tipo de Procedimento cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/procedimento-tipo/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->procedimento = $procedimentoTipo;
            }
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $procedimentoTipo = $this->_procedimentoTipoRepository->getById($id);

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();
            $this->_setData($procedimentoTipo, $data);

            $validate = $this->_validate($procedimentoTipo);
            if (!isset($validate) || $validate == "") {
                $procedimentoTipo->save();

                $this->_helper->FlashMessenger('Tipo de Procedimento alterado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/procedimento-tipo/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->procedimento = $procedimentoTipo;
            }
        }
        $this->view->procedimento = $procedimentoTipo;
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $procedimentoTipo = $this->_procedimentoTipoRepository->getById($id);

        $procedimentoTipo->status = ProcedimentoTipo::EXCLUIDO;
        $procedimentoTipo->save();

        $this->_helper->FlashMessenger('TIpo de Procedimento removido com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/procedimento-tipo/');
    }

    private function _setData(ProcedimentoTipo $procedimentoTipo, $data) {

        $procedimentoTipo->descricao = $data['descricao'];
        $procedimentoTipo->status = $data['status'];
        $procedimentoTipo->tipo = $data['tipo'];
        $procedimentoTipo->intervalo_dias = (int) $data['manutencao'];
        $procedimentoTipo->valor = AppUtil::convertStringToFloat($data['valor']);
        $procedimentoTipo->procedimento_categoria = $data['categoria'];
        $procedimentoTipo->empresa_id = $this->_empresa_id;

    }

    private function _validate(ProcedimentoTipo $procedimentoTipo) {
        if ($procedimentoTipo->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        if ($procedimentoTipo->valor <= 0)
            $result .= "<li>O campo <b>Valor</b> deve ser informado.</li>";

        if ($procedimentoTipo->tipo <= 0)
            $result .= "<li>O campo <b>Tipo</b> deve ser informado.</li>";

        $result .= $this->_validateDescricaoTipo($procedimentoTipo);

        return $result;
    }

    private function _validateDescricaoTipo(ProcedimentoTipo $procedimentoTipo) {
        if ($this->_procedimentoTipoRepository->isExiste($procedimentoTipo, $this->_empresa_id)) {
            $result .= "<li>Já existe um Tipo de Procedimento com essa <b>Descrição</b> e com o mesmo <b>Tipo</b></li>";
        }

        return $result;
    }

}
