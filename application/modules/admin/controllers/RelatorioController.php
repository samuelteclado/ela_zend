<?php

class Admin_RelatorioController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_contaBancariaRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_planodecontasRepository;
    private $_clienteRepository;
    private $_empresa_id;

    public function init() {
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_empresaRepository = new EmpresaRepository();
        $this->_lancamentoRecorrenciaTipoRepostirory = new LancamentoRecorrenciaTipoRepository();
        $this->_planodecontasRepository = new PlanodeContasRepository();
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

    public function mensalidadeAction() {
        $params = $this->getRequest()->getParams();
        $filter = new RepositoryFilter($params);
        $filter->addFilter('escola_id = ?', $this->_empresa_id);
        $filter->addFilter('p.status != ?', Aluno::EXCLUIDO);



        $filter->addSelectFilter('u.id', $params['unidade']);
        $filter->addSelectFilter('c.id', $params['curso']);
        $filter->addSelectFilter('t.id', $params['turma']);
        $filter->addSelectFilter('p.id', $params['aluno']);
        $filter->addLeftJoinFilter('p.Turma t');
        $filter->addLeftJoinFilter('t.Curso c');
        $filter->addLeftJoinFilter('t.Unidade u');


        $this->view->unidade_obj = $this->_unidadeRepository->getById($params['unidade'], $this->_empresa_id);
        $this->view->curso_obj = $this->_cursoRepository->getById($params['curso'], $this->_empresa_id);
        $this->view->aluno_obj = $this->_alunoRepository->getById($params['aluno'], $this->_empresa_id);
        $this->view->turma_obj = $this->_turmaRepository->getById($params['turma'], $this->_empresa_id);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_alunoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(500);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);
        $this->view->page = 'Mensalidade Escolar';

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->produtos = $this->_produtoRepository->getListByEscola($this->_empresa_id);
        $this->view->escola = $this->_escolaRepository->getById($this->_empresa_id);
        $this->view->unidades = $this->_unidadesRepository->getUnidades($this->_empresa_id);
        $this->view->alunos = $this->_alunoRepository->getListActive($this->_empresa_id);
        $this->view->cursos = $this->_cursoRepository->getListByEscola($this->_empresa_id);
        $this->view->turmas = $this->_turmaRepository->getListByEscola($this->_empresa_id);

        $tabela = array();
        $produtos_modelo = $this->_produtoRepository->getListByEscola($this->_empresa_id);

        $produtos_array = array();
        foreach ($produtos_modelo as $produto) {
            $produtos_array[$produto->id] = NULL;
            $produto_array_modelo[$produto->id] = $produto->sigla;
        }

        $alunos = array();
        $alunos[0][2] = $produto_array_modelo;
        foreach ($list as $aluno) {
            $alunos[$aluno->id][0] = $aluno->nome;
            $alunos[$aluno->id][1] = $aluno->matricula;
            $alunos[$aluno->id][2] = $produtos_array;
        }

        $produto_aprovados = $this->_produtoAlunoRepository->getListAprovadorByEscola($this->_empresa_id);

        foreach ($produto_aprovados as $produto_aprovado) {
            $alunos[$produto_aprovado->aluno_id][2][$produto_aprovado->ProdutoCurso->produto_id] = $produto_aprovado->valor_parcela;
        }


        foreach ($alunos as $aluno) {
            foreach ($list->ProdutoAluno as $produto) {
                $tabela[$aluno->id][$produto->id] = $this->_produtoAlunoRepository->getProdutoAprovadoByAluno($produto->id, $aluno->id, $this->_empresa_id);
            }
        }

        $this->view->tabela = $alunos;
    }

    public function extratoAction() {
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
        $filter->addFilter('tipo != ?', Lancamento::MENSALIDADE);
        $filter->addNotNullFilter('pagamento_data');
        $filter->addSelectFilter('conta_bancaria_id', $params['banco']);
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
        $this->view->alunos = $this->_alunoRepository->getListActive($this->_empresa_id);
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
