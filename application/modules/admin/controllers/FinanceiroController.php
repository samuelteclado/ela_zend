<?php

class Admin_FinanceiroController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_procedimentoTipoRepository;
    private $_pagamentoTipoRepository;
    private $_planodecontasRepository;
    private $_contaBancariaRepository;
    private $_clienteRepository;
    private $_fornecedorRepository;
    private $_usuarioRepository;
    private $_empresaRepository;
    private $_empresa_id;

    public function init() {
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_empresaRepository = new EmpresaRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
        $this->_procedimentoTipoRepository = new ProcedimentoTipoRepository();
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_lancamentoRecorrenciaTipoRepostirory = new LancamentoRecorrenciaTipoRepository();
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_contaBancariaRepository = new ContaBancariaRepository();
        $this->_clienteRepository = new ClienteRepository();
        $this->_fornecedorRepository = new FornecedorRepository();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $params['ano'] = ($params['ano']) ? $params['ano'] : date('Y');
        $params['mes'] = ($params['mes']) ? $params['mes'] : date('m');
        $ano = $params['ano'];
        $mes = $params['mes'];
        $ultimo_dia = date("d", mktime(0, 0, 0, ($mes + 1), 0, $ano));

        $filter = new RepositoryFilter($params);
        $filter->addGenericFilter('(vencimento_data BETWEEN "' . date("$ano-$mes-01") . '" AND "' . date("$ano-$mes-$ultimo_dia") . '" AND vencimento_data_atualizada IS NULL ) OR (vencimento_data_atualizada BETWEEN "' . date("$ano-$mes-01") . '" AND "' . date("$ano-$mes-$ultimo_dia") . '")');
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status != ?', Lancamento::EXCLUIDO);
        $filter->addSelectFilter('tipo', $params['tipo']);
        $filter->addSelectFilter('cliente_id', $params['cliente']);
        $filter->addSelectFilter('fornecedor_id', $params['fornecedor']);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addDateFilter('vencimento_data', $params['vencimento_data']);
        $filter->addLeftJoinFilter("p.Cliente c");
        $filter->addLeftJoinFilter("p.Fornecedor f");
        if ($params['situacao'] == Lancamento::ABERTO) {
            $filter->addNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::LIQUIDADO) {
            $filter->addNotNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::VENCIDO) {
            $filter->addGenericFilter('(vencimento_data < "' . date('Y-m-d').'")');
            $filter->addNullFilter('pagamento_data');
        }


        $sortParam = ($params["sort"]) ? $params["sort"] : 'vencimento_data';

        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(1000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $orderby->addOrder($params["sort"], ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
        $this->view->fornecedores = $this->_fornecedorRepository->getList($this->_empresa_id);

        $this->view->params = $params;
        $this->view->meses = AppUtil::getMeses();
        $data_atual = date("Y-m-d");
        $this->view->data_atual = $data_atual;
    }


    public function extratoPeriodoAction() {
        $params = $this->getRequest()->getParams();
        $filter = new RepositoryFilter($params);

        if ($this->getRequest()->isPost()) {


            $data_inicial = AppUtil::convertStringToDate($params['data_inicial']);
            $data_final = AppUtil::convertStringToDate($params['data_final']);
            $plano_de_contas_filtro = $params['plano_de_contas'];

            if ($data_inicial == NULL) {
                $data_inicial = $this->_lancamentoRepository->getDatePrimeiroLancamento($this->_empresa_id);
                $data_inicial = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_inicial)) . "-1 day"));
            }

            $data_final_atualizada = $data_final;
            if ($data_final == NULL) {
                $data_final = date('Y' . '-12-31');
                $data_final_atualizada = NULL;
            }


            if ($plano_de_contas_filtro != NULL) {
                $planos_de_contas_id = array();
                foreach ($plano_de_contas_filtro as $plano_de_contas_item) {
                    $plano_de_conta_obj = $this->_planodecontasRepository->getById($plano_de_contas_item);
                    if ($plano_de_conta_obj->PlanoDeContas->count() > 0) {
                        foreach ($plano_de_conta_obj->PlanoDeContas as $plano_de_contas_filho) {
                            $planos_de_contas_id[] = $plano_de_contas_filho->id;
                        }
                    }
                    $planos_de_contas_id[] = $plano_de_contas_item;
                }
                $filter->addGenericFilter('plano_de_contas_id IN (' . implode(",", $planos_de_contas_id) . ')');
            }

            $planos_de_contas_obj = array();
            $plano_de_contas_impressao = array();
            foreach ($plano_de_contas_filtro as $plano_de_contas_item) {
                $plano_de_conta_obj = $this->_planodecontasRepository->getById($plano_de_contas_item);
                $planos_de_contas_obj['codigo'] = $plano_de_conta_obj->codigo;
                $planos_de_contas_obj['descricao'] = $plano_de_conta_obj->descricao;
                $plano_de_contas_impressao[] = $planos_de_contas_obj;
            }

            $this->view->plano_de_contas_obj = $plano_de_contas_impressao;

            $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
        } else {

            $data_inicial = date('Y-m-1');
            $ultimo_dia = date("d", mktime(0, 0, 0, (date('m') + 1), 0, date('Y')));
            $data_final = date('Y-m-' . $ultimo_dia);
        }


        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addSelectFilter('tipo', $params['tipo_lancamento']);
        $filter->addNotNullFilter('pagamento_data');
        $filter->addFilter('pagamento_data BETWEEN ? AND ?', array($data_inicial, $data_final));
        $filter->addLeftJoinFilter('p.PlanoDeContas pc');

        $sortParam = ($params["sort"]) ? $params["sort"] : 'pagamento_data';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Extrato ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getListActive($this->_empresa_id);
        $receita = $this->_lancamentoRepository->getReceitaByDate($this->_empresa_id, $data_inicial);
        $despesa = $this->_lancamentoRepository->getDespesaByDate($this->_empresa_id, $data_inicial);

        $saldo = $receita - $despesa;
        $this->view->saldo_anterior = $saldo;
        $this->view->plano_de_contas_list = $plano_de_contas_filtro;
        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final_atualizada;

        $this->view->params = $params;
        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);

        $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
    }


    public function extratoProcedimentosAction() {
        $params = $this->getRequest()->getParams();
        $filter = new RepositoryFilter($params);


        if ($this->getRequest()->isPost()) {


            $data_inicial = AppUtil::convertStringToDate($params['data_inicial']);
            $data_final = AppUtil::convertStringToDate($params['data_final']);
            $plano_de_contas_filtro = $params['plano_de_contas'];

            if ($data_inicial == NULL) {
                $data_inicial = date("Y-m-t");
            }

            $data_final_atualizada = $data_final;
            if ($data_final == NULL) {
                $data_final = date('Y' . '-12-31');
                $data_final_atualizada = NULL;
            }

        } else {

            $data_inicial = date('Y-m-1');
            $ultimo_dia = date("d", mktime(0, 0, 0, (date('m') + 1), 0, date('Y')));
            $data_final = date('Y-m-' . $ultimo_dia);
        }


        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addNotNullFilter('pagamento_data');
        $filter->addFilter('pagamento_data BETWEEN ? AND ?', array($data_inicial, $data_final));
        $filter->addNotNullFilter("procedimento_id");
        $filter->addLeftJoinFilter("p.Procedimento pr");
        $filter->addSelectFilter('pr.usuario_id', $params['colaborador']);
        $filter->addFilter('pr.tipo = ?', Procedimento::CILIOS);
        $filter->addFilter('pr.status = ?', Procedimento::REALIZADO);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'pagamento_data';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(2000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Extrato ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getListActive($this->_empresa_id);

        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final_atualizada;

        $this->view->params = $params;
        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->pagamento_tipo = $this->_preparaFormadePagamento($list);
        $this->view->procedimentos = $this->_preparaProcedimentos($list);
        $this->view->colaboradores = $this->_usuarioRepository->getFuncionariosByEmpresa($this->_empresa_id);
        $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
    }


    public function comissaoAction() {
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
        $data_final = date("Y-m-t", strtotime($data_inicial));

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('tipo = ?', Lancamento::RECEITA);
        $filter->addNotNullFilter('pagamento_data');
        $filter->addNotNullFilter('procedimento_id');
        $filter->addLeftJoinFilter("p.Procedimento pr");
        $filter->addSelectFilter('pr.usuario_id', $params['colaborador']);
        $filter->addFilter('pagamento_data BETWEEN ? AND ?', array($data_inicial, $data_final));
        $filter->addFilter('status != ?', Lancamento::EXCLUIDO);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'pagamento_data';

        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Comissao ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);

        $this->view->repository_filter = $filter;
        $this->view->colaboradores = $this->_usuarioRepository->getFuncionariosByEmpresa($this->_empresa_id);

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->funcionario = $this->_usuarioRepository->getById($params['colaborador']);
        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final;
        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();
        $this->view->params = $params;

    }

    public function contasReceberAction() {
        $params = $this->getRequest()->getParams();

        $data_inicial = AppUtil::convertStringToDate($params['data_inicial']);
        $data_final = AppUtil::convertStringToDate($params['data_final']);

        if ($data_inicial == NULL)
            $data_inicial = date('Y-m-01');

        if ($data_final == NULL)
            $data_final = date("Y-m-t");


        $filter = new RepositoryFilter($params);
        $filter->addSelectFilter('pagamento_tipo_id', $params['forma_pagamento']);
        $filter->addSelectFilter('cliente_id', $params['cliente']);
        $filter->addFilter('tipo = ?', Lancamento::RECEITA);
        $filter->addFilter('status != ?', Lancamento::EXCLUIDO);
        $filter->addFilter('vencimento_data BETWEEN ? AND ?', array($data_inicial, $data_final));
        if ($params['situacao'] == Lancamento::ABERTO) {
            $filter->addNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::LIQUIDADO) {
            $filter->addNotNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::VENCIDO) {
            $filter->addGenericFilter('vencimento_data < "' . date('Y-m-d') . '"');
            $filter->addNullFilter('pagamento_data');
        }

        $sortParam = ($params["sort"]) ? $params["sort"] : 'vencimento_data';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(1000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Saldo ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);


        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final;
        $this->view->situacao_pagamento = $this->_getSituacao($params['situacao']);
        $this->view->forma_pagamentos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getList();
        $this->view->params = $params;

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);

    }

    public function contasPagarAction() {
        $params = $this->getRequest()->getParams();

        $data_inicial = AppUtil::convertStringToDate($params['data_inicial']);
        $data_final = AppUtil::convertStringToDate($params['data_final']);

        if ($data_inicial == NULL)
            $data_inicial = date('Y-m-01');

        if ($data_final == NULL)
            $data_final = date("Y-m-t");


        $filter = new RepositoryFilter($params);
        $filter->addSelectFilter('pagamento_tipo_id', $params['forma_pagamento']);
        $filter->addSelectFilter('fornecedor_id', $params['fornecedor']);
        $filter->addFilter('tipo = ?', Lancamento::DESPESA);
        $filter->addFilter('status != ?', Lancamento::EXCLUIDO);
        $filter->addFilter('vencimento_data BETWEEN ? AND ?', array($data_inicial, $data_final));
        if ($params['situacao'] == Lancamento::ABERTO) {
            $filter->addNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::LIQUIDADO) {
            $filter->addNotNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::VENCIDO) {
            $filter->addGenericFilter('vencimento_data < "' . date('Y-m-d') . '"');
            $filter->addNullFilter('pagamento_data');
        }

        $sortParam = ($params["sort"]) ? $params["sort"] : 'vencimento_data';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(1000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Saldo ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);


        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final;
        $this->view->situacao_pagamento = $this->_getSituacao($params['situacao']);
        $this->view->forma_pagamentos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa_id);
        $this->view->params = $params;

        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);

    }

    public function fluxoCaixaAction() {

        if ($this->getRequest()->isPost()) {

            $params = $this->getRequest()->getParams();
            $params['ano'] = (!$params['ano']) ? date('Y') : $params['ano'];
            $params['mes'] = (!$params['mes']) ? date('m') : $params['mes'];
            $params['periodo'] = (!$params['periodo']) ? 12 : $params['periodo'];
        } else {
            $params = $this->getRequest()->getParams();
            $params['ano'] = date('Y');
            $params['mes'] = date('m');
            $params['periodo'] = 12;
        }


        $data_final = date("Y-m-d", mktime(0, 0, 0, $params['mes'], 1, $params['ano']));
        $data_inicial = date("Y-m-t", strtotime(date("Y-m-d", strtotime($data_final)) . "-" . ($params['periodo'] - 1) . " month"));

        $ano_inicial = date('Y',strtotime($data_inicial));
        $mes_inicial = date('m',strtotime($data_inicial));


        if(!$this->getRequest()->isPost())
            $params['mes'] = $mes_inicial;



        $fruxo_de_caixa_db = $this->_lancamentoRepository->getFluxoDeCaixaGeral($this->_empresa_id, $data_inicial, $data_final, $params['conta_bancaria'], $params['cliente']);
        $fruxo_de_caixa = $this->_prepareFluxoDeCaixa($fruxo_de_caixa_db, $ano_inicial, $mes_inicial, $params['periodo']);

        $this->view->fluxo_de_caixa = $fruxo_de_caixa;
        $this->view->clientes = $this->_getClientes($fruxo_de_caixa_db);

        $this->view->contas_bancarias = $this->_contaBancariaRepository->getListById($this->_empresa_id, $params['conta_bancaria']);
        $this->view->filtro_contas_bancarias = $this->_contaBancariaRepository->getListActive($this->_empresa_id);

        $this->view->intervalos = $this->_getIntervalos($ano_inicial, $mes_inicial, $params['periodo']);
        $this->view->filtro_clientes = $this->_clienteRepository->getListActive($this->_empresa_id);

        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();

        $this->view->params = $params;
        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
    }

    private function _preparaFormadePagamento($lancamentos){
        $tipos_pagamento_obj = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);

        $tipos_pagamentos = $this->_preparaTipodeLancamentos($tipos_pagamento_obj);

        $forma_pagamento_pronto = array();

        foreach ($tipos_pagamentos as $pagamento){

            $forma_pagamento['tipo_pagamento']['descricao'] = array();
            $forma_pagamento['tipo_pagamento']['id'] = array();
            $forma_pagamento['tipo_pagamento']['qt_total'] = 0;
            $forma_pagamento['tipo_pagamento']['valor_total'] = 0;
            $qt = 1;

            foreach ($lancamentos as $lancamento){
                $forma_pagamento['tipo_pagamento']['descricao'] = $pagamento['descricao'];
                if($lancamento->pagamento_tipo_id == $pagamento['id']){
                    $forma_pagamento['tipo_pagamento']['valor_total'] += $lancamento->pagamento_valor;
                    $forma_pagamento['tipo_pagamento']['qt_total'] = $qt++;
                }
            }

            $forma_pagamento_pronto[]= $forma_pagamento;
        }

        return $forma_pagamento_pronto;
    }

    private function _preparaProcedimentos($lancamentos){
        $procedimento_tipos_obj = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id, ProcedimentoTipo::CILIOS);

        $procedimentos_tipos = $this->_preparaProcedimentosTipo($procedimento_tipos_obj);

        $procedimentos_tipos_pronto = array();

        foreach ($procedimentos_tipos as $procedimento_tipo){

            $procedimento_tipo['procedimento_tipo']['descricao'] = array();
            $procedimento_tipo['procedimento_tipo']['id'] = array();
            $procedimento_tipo['procedimento_tipo']['tipo'] = array();
            $procedimento_tipo['procedimento_tipo']['qt_total'] = 0;
            $procedimento_tipo['procedimento_tipo']['tipo_total'] = 0;
            $qt = 0;

            foreach ($lancamentos as $lancamento){
                //if($procedimento_tipo['procedimento_tipo']['tipo'] == ProcedimentoTipo::APLICACAO){
                    $procedimento['procedimento_tipo']['descricao'] = $procedimento_tipo['descricao'];
                    if($lancamento->Procedimento->procedimento_tipo_id == $procedimento_tipo['id']){
                        $procedimento['procedimento_tipo']['qt_total'] = $qt++;
                    }

                //}

            }

            $procedimentos_tipos_pronto[]= $procedimento;
        }

        return $procedimentos_tipos_pronto;
    }


    private function _prepareFluxoDeCaixa($fluxo_de_caixa, $ano_inicial, $mes_inicial, $periodo_meses) {

        $intervalos = $this->_getIntervalos($ano_inicial, $mes_inicial, $periodo_meses);

        $fluxo_de_caixa_pronto = array();

        foreach ($intervalos as $intervalo) {

            $intervalo['receitas']['total'] = 0;
            $intervalo['receitas']['clientes'] = array();
            $intervalo['receitas']['contas_bancarias'] = array();
            $intervalo['despesas']['total'] = 0;
            $intervalo['despesas']['clientes'] = array();
            $intervalo['despesas']['contas_bancarias'] = array();

            foreach ($fluxo_de_caixa as $caixa) {

                if ($caixa['data'] == $intervalo['id']) {

                    if ($caixa['tipo'] == Lancamento::RECEITA) {
                        $intervalo['receitas']['total'] += $caixa['valor'];

                        $cliente = array();
                        $cliente['cliente_id'] = $caixa['cliente_id'];
                        $cliente['cliente_nome'] = $caixa['cliente_nome'];
                        $cliente['cliente_sobrenome'] = $caixa['cliente_sobrenome'];
                        $cliente['cliente_valor'] = $caixa['valor'];
                        $intervalo['receitas']['clientes'][$caixa['cliente_id']] = $cliente;

                        $intervalo['receitas']['contas_bancarias'][$caixa['conta']]['conta_bancaria'] = $caixa['conta'];
                        $intervalo['receitas']['contas_bancarias'][$caixa['conta']]['conta_valor'] += $caixa['valor'];
                    }

                    if ($caixa['tipo'] == Lancamento::DESPESA) {
                        $intervalo['despesas']['total'] += $caixa['valor'];

                        $cliente = array();
                        $cliente['cliente_id'] = $caixa['cliente_id'];
                        $cliente['cliente_nome'] = $caixa['cliente_nome'];
                        $cliente['cliente_sobrenome'] = $caixa['cliente_sobrenome'];
                        $cliente['cliente_valor'] = $caixa['valor'];
                        $intervalo['despesas']['clientes'][$caixa['cliente_id']] = $cliente;

                        $intervalo['despesas']['contas_bancarias'][$caixa['conta']]['conta_bancaria'] = $caixa['conta'];
                        $intervalo['despesas']['contas_bancarias'][$caixa['conta']]['conta_valor'] += $caixa['valor'];
                    }
                }
            }

            $fluxo_de_caixa_pronto[] = $intervalo;
        }

        return $fluxo_de_caixa_pronto;
    }

    private function _preparaTipodeLancamentos($tiposdeLancamentos){
       $tipos_lancamentos = array();
       $tipo_lancamento = array();

       foreach ($tiposdeLancamentos as $tipodelancamento){

           $tipo_lancamento['id'] = $tipodelancamento->id;
           $tipo_lancamento['descricao'] = $tipodelancamento->descricao;

           $tipos_lancamentos[] = $tipo_lancamento;
       }

       return $tipos_lancamentos;
    }

    private function _preparaProcedimentosTipo($procedimentos){
        $procedimentos_tipos = array();
        $procedimento_tipo = array();

        foreach ($procedimentos as $procedimento){

            $procedimento_tipo['id'] = $procedimento->id;
            $procedimento_tipo['descricao'] = $procedimento->descricao;
            $procedimento_tipo['tipo'] = $procedimento->tipo;

            $procedimentos_tipos[] = $procedimento_tipo;
        }

        return $procedimentos_tipos;
    }

    private function _getIntervalos($ano_inicial, $mes_inicial = 1, $periodo_meses = 12) {
        $intervalos = array();
        $intervalo = array();

        $ano = $ano_inicial;
        $mes = $mes_inicial;

        for ($index = 0; $index < $periodo_meses; $index++) {
            $intervalo['id'] = $ano . str_pad($mes, 2, "0", STR_PAD_LEFT);
            $intervalo['ano'] = $ano;
            $intervalo['mes'] = $mes;

            $intervalos[] = $intervalo;

            if ($mes < 12) {
                $mes++;
            } else {
                $mes = 1;
                $ano++;
            }
        }

        return $intervalos;
    }

    private function _getClientes($fluxo_de_caixa) {
        $clientes = array();
        $clientes['receitas'] = array();
        $clientes['despesas'] = array();

        foreach ($fluxo_de_caixa as $caixa) {
            $cliente = array();
            $cliente['cliente_id'] = $caixa['cliente_id'];
            $cliente['cliente_nome'] = $caixa['cliente_nome'];
            $cliente['cliente_sobrenome'] = $caixa['cliente_sobrenome'];

            if ($caixa['tipo'] == Lancamento::RECEITA) {
                if (!$clientes['receitas'][$caixa['cliente_id']])
                    $clientes['receitas'][$caixa['cliente_id']] = $cliente;
            }else if ($caixa['tipo'] == Lancamento::DESPESA) {
                if (!$clientes['despesas'][$caixa['cliente_id']])
                    $clientes['despesas'][$caixa['cliente_id']] = $cliente;
            }
        }

        return $clientes;
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

    private function _getSituacao($situacao_do_pagamento) {
        switch ($situacao_do_pagamento) {

            case Lancamento::ABERTO:
                return 'Á Pagar';

            case Lancamento::LIQUIDADO:
                return 'Liquidado';

            case Lancamento::VENCIDO:
                return 'Vencido';

            default:
                return 'Todos';
        }
    }

    private function _getFormaPagamento($forma_pagamento) {
        switch ($forma_pagamento) {

            case Lancamento::CHEQUE:
                return 'Cheque';

            case Lancamento::DEPOSITO:
                return 'Depósito';

            case Lancamento::DINHEIRO:
                return 'Dinheiro';

            case Lancamento::ABERTO:
                return 'Em Aberto';

            default:
                return 'Todos';
        }
    }

}
