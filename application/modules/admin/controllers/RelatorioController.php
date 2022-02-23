<?php

class Admin_RelatorioController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_planodecontasRepository;
    private $_clienteRepository;
    private $_fornecedorRepository;
    private $_empresaRepository;
    private $_empresa_id;

    public function init() {
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_lancamentoRecorrenciaTipoRepostirory = new LancamentoRecorrenciaTipoRepository();
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_empresaRepository = new EmpresaRepository();
        $this->_fornecedorRepository = new FornecedorRepository();
        $this->_clienteRepository = new ClienteRepository();
    }

    public function boletosAction() {
        $params = $this->getRequest()->getParams();
        $filter = new RepositoryFilter($params);

        if ($this->getRequest()->isPost()) {


            $data_inicial = AppUtil::convertStringToDate($params['data_inicial']);
            $data_final = AppUtil::convertStringToDate($params['data_final']);
            $alunos_filtro = $params['alunos'];

            if ($data_inicial == NULL) {
                $data_inicial = $this->_lancamentoRepository->getDatePrimeiroLancamento($this->_empresa_id);
                $data_inicial = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data_inicial)) . "-1 day"));
            }

            $data_final_atualizada = $data_final;
            if ($data_final == NULL) {
                $data_final = date('Y' . '-12-31');
                $data_final_atualizada = NULL;
            }


            if ($alunos_filtro != NULL) {
                $aluno_id = array();
                foreach ($alunos_filtro as $aluno_item) {
                    $aluno_id[] = $aluno_item;
                }
                $filter->addGenericFilter('aluno_id IN (' . implode(",", $aluno_id) . ')');
            }

            $alunos_obj = array();
            $alunos_impressao = array();
            foreach ($alunos_filtro as $aluno_item) {
                $aluno_obj = $this->_alunoRepository->getById($aluno_item);
                $alunos_obj['nome'] = AppUtil::resumeTexto($aluno_obj->nome, 20);
                $alunos_impressao[] = $alunos_obj;
            }
            $this->view->alunos_obj = $alunos_impressao;

            $banco_obj = $this->_contaBancariaRepository->getById($params['banco']);
            $this->view->banco_obj = AppUtil::resumeTexto($banco_obj->descricao, 15);
        } else {

            $data_inicial = date('Y-m-1');
            $ultimo_dia = date("d", mktime(0, 0, 0, (date('m') + 1), 0, date('Y')));
            $data_final = date('Y-m-' . $ultimo_dia);
        }


        $filter->addFilter('escola_id = ?', $this->_empresa_id);
        $filter->addFilter('tipo = ?', Lancamento::RECEITA);
        $filter->addSelectFilter('conta_bancaria_id', $params['banco']);
        $filter->addFilter('p.vencimento_data BETWEEN ? AND ?', array($data_inicial, $data_final));
        $filter->addJoinFilter('p.Boleto b');

        $sortParam = ($params["sort"]) ? $params["sort"] : 'vencimento_data';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Boletos Emitidos de ' . AppUtil::convertDateToString($data_inicial) . ' a ' . AppUtil::convertDateToString($data_final);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->alunos_list = $alunos_filtro;
        $this->view->data_inicial = $data_inicial;
        $this->view->data_final = $data_final_atualizada;

        $this->view->params = $params;
        $this->view->escola = $this->_escolaRepository->getById($this->_empresa_id);

        $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_empresa_id);
        $this->view->alunos = $this->_alunoRepository->getListByEscola($this->_empresa_id);
    }

    public function usuarioAction() {
        $params = $this->getRequest()->getParams();
        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status != ?', Cliente::EXCLUIDO);
        $filter->addSelectFilter('status', $params['status']);


        $this->view->clientes = $this->_clienteRepository->getById($params['cliente']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_clienteRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Clientes Espaço ELA';

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->empresa = $empresa;

    }


    public function fornecedorAction() {
        $params = $this->getRequest()->getParams();
        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addSelectFilter('status', $params['status']);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'descricao';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_fornecedorRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Clientes Espaço ELA';

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $empresa = $this->_empresaRepository->getById($this->_empresa_id);
        $this->view->empresa = $empresa;

    }


    public function fluxoCaixaAction() {

        if ($this->getRequest()->isPost()) {

            $params = $this->getRequest()->getParams();
            $params['ano'] = (!$params['ano']) ? date('Y') : $params['ano'];
            $params['mes'] = (!$params['mes']) ? date('m') : $params['mes'];
            $params['periodo'] = (!$params['periodo']) ? 6 : $params['periodo'];
        } else {
            $params = $this->getRequest()->getParams();
            $params['ano'] = date('Y');
            $params['mes'] = date('m');
            $params['periodo'] = 6;
        }


        $data_inicial = date("Y-m-d", mktime(0, 0, 0, $params['mes'], 1, $params['ano']));
        $data_final = date("Y-m-t", strtotime(date("Y-m-d", strtotime($data_inicial)) . "+" . ($params['periodo'] - 1) . " month"));

        $fruxo_de_caixa_db = $this->_lancamentoRepository->getFluxoDeCaixaGeral($this->_empresa_id, $data_inicial, $data_final, $params['conta_bancaria'], $params['aluno']);
        $fruxo_de_caixa = $this->_prepareFluxoDeCaixa($fruxo_de_caixa_db, $params['ano'], $params['mes'], $params['periodo']);

        $this->view->fluxo_de_caixa = $fruxo_de_caixa;
        $this->view->alunos = $this->_getAlunos($fruxo_de_caixa_db);

        $this->view->intervalos = $this->_getIntervalos($params['ano'], $params['mes'], $params['periodo']);
        $this->view->contas_bancarias = $this->_contaBancariaRepository->getListById($this->_empresa_id, $params['conta_bancaria']);
        $this->view->filtro_contas_bancarias = $this->_contaBancariaRepository->getListActive($this->_empresa_id);
        $this->view->filtro_alunos = $this->_alunoRepository->getListActive($this->_empresa_id);

        $this->view->anos = $this->_getAnos();
        $this->view->meses = $this->_getMeses();
        $this->view->params = $params;
        $this->view->empresa = $this->_empresaRepository->getById($this->_empresa_id);
    }

    private function _prepareFluxoDeCaixa($fluxo_de_caixa, $ano_inicial, $mes_inicial, $periodo_meses) {

        $intervalos = $this->_getIntervalos($ano_inicial, $mes_inicial, $periodo_meses);

        $fluxo_de_caixa_pronto = array();

        foreach ($intervalos as $intervalo) {

            $intervalo['receitas']['total'] = 0;
            $intervalo['receitas']['alunos'] = array();
            $intervalo['receitas']['contas_bancarias'] = array();
            $intervalo['despesas']['total'] = 0;
            $intervalo['despesas']['alunos'] = array();
            $intervalo['despesas']['contas_bancarias'] = array();

            foreach ($fluxo_de_caixa as $caixa) {

                if ($caixa['data'] == $intervalo['id']) {

                    if ($caixa['tipo'] == Lancamento::RECEITA) {
                        $intervalo['receitas']['total'] += $caixa['valor'];

                        $aluno = array();
                        $aluno['aluno_id'] = $caixa['aluno_id'];
                        $aluno['aluno_nome'] = $caixa['aluno_nome'];
                        $aluno['aluno_valor'] = $caixa['valor'];
                        $intervalo['receitas']['alunos'][$caixa['aluno_id']] = $aluno;

                        $intervalo['receitas']['contas_bancarias'][$caixa['conta']]['conta_bancaria'] = $caixa['conta'];
                        $intervalo['receitas']['contas_bancarias'][$caixa['conta']]['conta_valor'] += $caixa['valor'];
                    }

                    if ($caixa['tipo'] == Lancamento::DESPESA) {
                        $intervalo['despesas']['total'] += $caixa['valor'];

                        $aluno = array();
                        $aluno['aluno_id'] = $caixa['aluno_id'];
                        $aluno['aluno_nome'] = $caixa['aluno_nome'];
                        $aluno['aluno_valor'] = $caixa['valor'];
                        $intervalo['despesas']['alunos'][$caixa['aluno_id']] = $aluno;

                        $intervalo['despesas']['contas_bancarias'][$caixa['conta']]['conta_bancaria'] = $caixa['conta'];
                        $intervalo['despesas']['contas_bancarias'][$caixa['conta']]['conta_valor'] += $caixa['valor'];
                    }
                }
            }

            $fluxo_de_caixa_pronto[] = $intervalo;
        }

        return $fluxo_de_caixa_pronto;
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

    private function _getAlunos($fluxo_de_caixa) {
        $alunos = array();
        $alunos['receitas'] = array();
        $alunos['despesas'] = array();

        foreach ($fluxo_de_caixa as $caixa) {
            $aluno = array();
            $aluno['aluno_id'] = $caixa['aluno_id'];
            $aluno['aluno_nome'] = $caixa['aluno_nome'];

            if ($caixa['tipo'] == Lancamento::RECEITA) {
                if (!$alunos['receitas'][$caixa['aluno_id']])
                    $alunos['receitas'][$caixa['aluno_id']] = $aluno;
            }else if ($caixa['tipo'] == Lancamento::DESPESA) {
                if (!$alunos['despesas'][$caixa['aluno_id']])
                    $alunos['despesas'][$caixa['aluno_id']] = $aluno;
            }
        }

        return $alunos;
    }

    private function _getAnos() {
        $anos = array();
        $ano_atual = date('Y');

        for ($index = -3; $index < 4; $index++) {
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
