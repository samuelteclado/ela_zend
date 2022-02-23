<?php

class Funcionario_RelatorioController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_planodecontasRepository;
    private $_clienteRepository;
    private $_fornecedorRepository;
    private $_usuarioRepository;
    private $_empresaRepository;
    private $_procedimentoRepository;
    private $_empresa_id;

    public function init() {
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_empresaRepository = new EmpresaRepository();
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_lancamentoRecorrenciaTipoRepostirory = new LancamentoRecorrenciaTipoRepository();
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_clienteRepository = new ClienteRepository();
        $this->_procedimentoRepository = new ProcedimentoRepository();
        $this->_fornecedorRepository = new FornecedorRepository();
    }


    public function possivelRemarcacaoAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();
            $params['ano'] = (!$params['ano']) ? date('Y') : $params['ano'];
            $params['mes'] = (!$params['mes']) ? date('m') : $params['mes'];

        } else {
            $params = $this->getRequest()->getParams();
            $params['ano'] = date('Y');
            $params['mes'] = date('m');
        }

        $data_inicial = date("Y-m-d", mktime(0, 0, 0, $params['mes'], 1, $params['ano']));
        $data_final = date("Y-m-t");
        //$data_final = date("Y-m-t", strtotime($data_inicial));

        //var_dump($data_inicial, $data_final);
        //die;
        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status = ?', Cliente::ATIVO);
        $filter->addJoinFilter("p.Procedimento pr");
        //  $filter->addFilter('pagamento_data BETWEEN ? AND ?', array($data_inicial, $data_final));


        $list = $this->_clienteRepository->getListRemarcacoes($this->_empresa_id,$data_inicial, $data_final);
        $this->view->list = $list;
        $this->view->page = 'Cliente que não voltaram entre ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final;
        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();
        $this->view->params = $params;

    }


    public function procedimentoCanceladoAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();
            $params['periodo'] = (!$params['periodo']) ? 1 : $params['periodo'];
        } else {
            $params['periodo'] = 1;
        }

        $data_inicial = date("Y-m-d", mktime(0, 0, 0, $params['mes'], 1, $params['ano']));
        $data_final = date("Y-m-t", strtotime($data_inicial));

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        //$filter->addFilter('status = ?', Cliente::ATIVO);
        $filter->addLeftJoinFilter("p.Procedimento pr");
        $filter->addSelectFilter('pr.usuario_id', $params['colaborador']);
      //  $filter->addFilter('pagamento_data BETWEEN ? AND ?', array($data_inicial, $data_final));

        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_clienteRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Cliente que não voltaram entre ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);

        $this->view->repository_filter = $filter;
        $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->funcionario = $this->_usuarioRepository->getById($params['colaborador']);
        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final;
        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();
        $this->view->params = $params;

    }

    public function clientesGeralAction() {

        $params = $this->getRequest()->getParams();


        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status != ?', Cliente::EXCLUIDO);
        $filter->addLeftJoinFilter("p.Procedimento pr");
        if ($params['procedimento'] == Cliente::NAO) {
            $filter->addNullFilter('pr.id');
        } elseif ($params['procedimento'] == Cliente::SIM) {
            $filter->addNotNullFilter('pr.id');
        }


        //$filter->addFilter('status = ?', Procedimento::ABERTO);
        //$filter->addNullFilter('data_confirmacao');

        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_clienteRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Cliente à confirmar ';

        $this->view->repository_filter = $filter;
        $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->funcionario = $this->_usuarioRepository->getById($params['colaborador']);

        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();
        $this->view->params = $params;

    }


    public function clientesConfirmarAction() {
        if ($this->getRequest()->isPost()) {
            $params = $this->getRequest()->getParams();
            $params['periodo'] = (!$params['periodo']) ? 1 : $params['periodo'];
        } else {
            $params['periodo'] = 1;
        }

        $data_inicial = date("Y-m-d", mktime(0, 0, 0, $params['mes'], 1, $params['ano']));
        $data_final = date("Y-m-t", strtotime($data_inicial));

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status = ?', Procedimento::ABERTO);
        $filter->add('status = ?', Procedimento::ABERTO);
        $filter->addNullFilter('data_confirmacao');

        $sortParam = ($params["sort"]) ? $params["sort"] : 'data';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_procedimentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Cliente à confirmar ';

        $this->view->repository_filter = $filter;
        $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->funcionario = $this->_usuarioRepository->getById($params['colaborador']);
        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final;
        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();
        $this->view->params = $params;

    }

    private function _getAnos() {
        $anos = array();
        $ano_atual = date('Y');

        for ($index = -2; $index < 3; $index++) {
            $anos[] = $ano_atual + $index;
        }

        return $anos;
    }

    private function _getMeses() {
        $meses = array();
        $mes = array();


        for ($index = 1; $index <= 12; $index++) {
            $mes['id'] = $index;
            $mes['text'] = AppUtil::getTextMonth($index);
            $meses[] = $mes;
        }

        return $meses;
    }

}
