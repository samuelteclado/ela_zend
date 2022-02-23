<?php

class Admin_PlanoDeContasController extends Zend_Controller_Action {

    private $_planodecontasRepository;
    private $_lancamentoRepository;
    private $_empresa_id;
    private $_layout;

    public function init() {
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_lancamentoRepository = new LancamentoRepository();
        $params = $this->getRequest()->getParams();
        $this->_layout = $params['nolayout'];
        $this->_helper->layout->setLayout(ViewUtil::disableLayout($this->_layout));
        $this->view->layout = $this->_layout;
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addNotNullFilter('codigo');
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addSelectFilter('tipo', $params['tipo']);
        $filter->addSelectFilter('natureza_operacao', $params['natureza']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'codigo';
        $orderParam = ($params["order"]) ? $params["order"] : 'ASC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'ASC' : 'DESC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_planodecontasRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(30);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $plano_de_contas = new PlanoDeContas();

            $data = $this->getRequest()->getPost();

            $this->_setDataNovo($plano_de_contas, $data);

            $validate = $this->_validate($plano_de_contas);

            if (!isset($validate) || $validate == "") {
                $plano_de_contas->save();

                $this->_helper->FlashMessenger('Plano de Contas cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/plano-de-contas/?nolayout=' . $this->_layout);
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->plano_de_contas = $plano_de_contas;
                $this->view->planos_pais = $this->_planodecontasRepository->getListByPlanosPai($this->_empresa_id);
            }
        } else {
            $this->view->layout = $this->_layout;
            $this->view->planos_pais = $this->_planodecontasRepository->getListByPlanosPai($this->_empresa_id);
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');

        $plano_de_contas = $this->_planodecontasRepository->getById($id, $this->_empresa_id);

        if ($plano_de_contas != FALSE) {

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setDataEdit($plano_de_contas, $data);

                $validate = $this->_validate($plano_de_contas);
                if (!isset($validate) || $validate == "") {
                    $plano_de_contas->save();

                    $this->_helper->FlashMessenger('Plano de contas alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/plano-de-contas/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->plano_de_contas = $plano_de_contas;
                    $this->view->planos_pais = $this->_planodecontasRepository->getListByPlanosPai($this->_empresa_id);
                }
            } else {
                $this->view->plano_de_contas = $plano_de_contas;
                $this->view->planos_pais = $this->_planodecontasRepository->getListByPlanosPai($this->_empresa_id);
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando acessar um plano de contas que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/plano-de-contas/');
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $plano_de_contas = $this->_planodecontasRepository->getById($id, $this->_empresa_id);

        if ($plano_de_contas != FALSE) {
            $qt_lancamentos = $this->_lancamentoRepository->getLancamentosByPlanodeContas($this->_empresa_id, $plano_de_contas->id)->count();

            if ($qt_lancamentos > 0) {
                $this->_helper->FlashMessenger(array('warning' => 'Atenção este plano de contas possui lançamentos vinculados. <a onclick="return confirm(\'Confirma a exclusão deste plano de contas mesmo assim ?\')" href="' . $this->view->baseUrl() . '/admin/plano-de-contas/deletar-forcado/id/' . $id . '">[ Forçar exclusão ]</a>'));
            } else if ($plano_de_contas->PlanoDeContas->count() > 0) {
                $this->_helper->FlashMessenger(array('warning' => 'Atenção este plano de contas vinculados a este plano de contas. <a onclick="return confirm(\'Confirma a exclusão de todos os planos de contas filho e deste plano de contas mesmo assim ?\')" href="' . $this->view->baseUrl() . '/admin/plano-de-contas/deletar-forcado/id/' . $id . '">[ Forçar exclusão ]</a>'));
            } else {
                $plano_de_contas->delete();
                $this->_helper->FlashMessenger('Plano de Contas removido com sucesso.');
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um plano de contas que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/plano-de-contas/?nolayout=' . $this->_layout);
    }

    public function deletarForcadoAction() {
        $id = $this->getRequest()->getParam('id');

        $plano_de_contas_obj = $this->_planodecontasRepository->getById($id, $this->_empresa_id);
        foreach ($plano_de_contas_obj->PlanoDeContas as $plano_de_contas) {
            if ($plano_de_contas != FALSE) {
                $lancamentos_list = $this->_lancamentoRepository->getLancamentosByPlanodeContas($this->_empresa_id, $plano_de_contas->id);
                foreach ($lancamentos_list as $lancamento) {
                    $lancamento->plano_de_contas_id = NULL;
                    $lancamento->save();
                }
                $plano_de_contas->delete();
                $this->_helper->FlashMessenger('Empréstimo removido com sucesso.');
            } else {
                $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um plano de contas que não existe.'));
            }
        }
        $this->_redirect($this->view->baseUrl() . '/admin/plano-de-contas/?nolayout=' . $this->_layout);
    }

    private function _setDataNovo(PlanoDeContas $plano_de_contas, $data) {

        $plano_de_contas->descricao = $data['descricao'];

        $plano_de_contas->tipo = $data['tipo'];
        $plano_de_contas->natureza_operacao = $data['natureza'];
        $plano_de_contas->codigo = $this->_planodecontasRepository->getCodPlanoPai($this->_empresa_id);

        $plano_de_contas->plano_de_contas_id = NULL;
        if ($plano_de_contas->tipo == PlanoDeContas::FILHO) {
            $plano_de_contas->plano_de_contas_id = $data['plano_pai'];
            $plano_pai = $this->_planodecontasRepository->getById($plano_de_contas->plano_de_contas_id);
            $plano_de_contas->codigo = $plano_pai->codigo . '.' . $this->_planodecontasRepository->getCodByPlano($this->_empresa_id, $plano_de_contas->plano_de_contas_id);
            $plano_de_contas->descricao = $data['descricao'];
        }
        $plano_de_contas->empresa_id = $this->_empresa_id;


    }

    private function _setDataEdit(PlanoDeContas $plano_de_contas, $data) {

        $plano_de_contas->descricao = $data['descricao'];

        $plano_de_contas->tipo = $data['tipo'];
        $plano_de_contas->natureza_operacao = $data['natureza'];

        $plano_de_contas->plano_de_contas_id = NULL;
        if ($plano_de_contas->tipo == PlanoDeContas::FILHO) {
            $plano_de_contas->plano_de_contas_id = $data['plano_pai'];
            $plano_pai = $this->_planodecontasRepository->getById($plano_de_contas->plano_de_contas_id);
            $plano_de_contas->codigo = $plano_pai->codigo . '.' . $this->_planodecontasRepository->getCodByPlano($this->_empresa_id, $plano_de_contas->plano_de_contas_id);
            $plano_de_contas->descricao = $data['descricao'];
        }
        $plano_de_contas->empresa_id = $this->_empresa_id;


    }

    private function _validate(PlanoDeContas $plano_de_contas) {
        if ($plano_de_contas->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";


        if ($plano_de_contas->tipo == PlanoDeContas::FILHO && $plano_de_contas->plano_de_contas_id <= 0)
            $result .= "<li>O campo <b>Plano de Contas Pai</b> deve ser informado.</li>";

        $result .= $this->_validatePlanoDeContasExistente($plano_de_contas);

        return $result;
    }

    private function _validatePlanoDeContasExistente(PlanoDeContas $plano_de_contas) {
        $plano_db = $this->_planodecontasRepository->getByDescricao($this->_empresa_id, $plano_de_contas->descricao);

        if ($plano_db->id > 0 && $plano_de_contas->id != $plano_db->id )
            return $result .= "<li>Já existe um <b>Plano de Contas</b> com este mesma descrição.</li>";

        return "";
    }

}
