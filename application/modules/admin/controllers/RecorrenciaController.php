<?php

class Admin_RecorrenciaController extends Zend_Controller_Action {

    private $_lancamentoRcorrenciaTipoRepository;

    public function init() {
        $this->_lancamentoRcorrenciaTipoRepository = new LancamentoRecorrenciaTipoRepository();
    }

    public function indexAction() {

        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addTextFilter('dias', $params['dias']);
        $filter->addSelectFilter('status', $params['status']);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'id';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRcorrenciaTipoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {
       

        if ($this->getRequest()->isPost()) {
            $recorrencia = new LancamentoRecorrenciaTipo();

            $data = $this->getRequest()->getPost();

            $this->_setData($recorrencia, $data);

            $validate = $this->_validate($recorrencia);

            if (!isset($validate) || $validate == "") {

                $recorrencia->save();

                $this->_helper->FlashMessenger('Recorrência cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/recorrencia/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->recorrencia = $recorrencia;
            }
        }
    }

    public function editarAction() {

            $id = $this->getRequest()->getParam('id');
            $recorrencia = $this->_lancamentoRcorrenciaTipoRepository->getById($id);

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($recorrencia, $data);

                $validate = $this->_validate($recorrencia);
                if (!isset($validate) || $validate == "") {
                    $recorrencia->save();

                    $this->_helper->FlashMessenger('Recorrência alterada com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/recorrencia/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->recorrencia = $recorrencia;
                }
            } else {
               
                $this->view->recorrencia = $recorrencia;
            }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $lancamento_recorrencia = $this->_lancamentoRcorrenciaTipoRepository->getById($id);
        $lancamento_recorrencia->delete();
        $this->_helper->FlashMessenger('Recorrência removida com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/recorrencia/');
    }

    private function _setData(LancamentoRecorrenciaTipo $recorrencia, $data) {
        $recorrencia->descricao = $data['descricao'];
        $recorrencia->dias = $data['dias'];
        $recorrencia->status = $data['status'];
    }

    private function _validate(LancamentoRecorrenciaTipo $recorrencia) {
        if ($recorrencia->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        if ($recorrencia->dias <= 0)
            $result .= "<li>O campo <b>Dias</b> deve ser informado.</li>";

             //$result .= $this->_validateCNPJ($recorrencia);

        return $result;
    }

    
  
}
