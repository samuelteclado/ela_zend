<?php

class Admin_LancamentoController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_planodecontasRepository;
    private $_pagamentoTipoRepository;
    private $_clienteRepository;
    private $_fornecedorRepository;
    private $_empresaRepository;
    private $_contaBancariaRepository;

    private $_empresa_id;

    public function init() {
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_empresaRepository = new EmpresaRepository();
        $this->_contaBancariaRepository = new ContaBancariaRepository();
        $this->_lancamentoRecorrenciaTipoRepostirory = new LancamentoRecorrenciaTipoRepository();
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_clienteRepository = new ClienteRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
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
        $filter->addSelectFilter('pt.id', $params['pagamento']);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addDateFilter('vencimento_data', $params['vencimento_data']);
        $filter->addSelectFilter('conta_bancaria_id', $params['conta_bancaria']);
        $filter->addLeftJoinFilter("p.Cliente c");
        $filter->addLeftJoinFilter("p.Procedimento pr");
        $filter->addLeftJoinFilter("pr.PagamentoTipo pt");
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
        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_lancamentoRepository->getListByFilter($filter, $orderby)));;

        $list->setItemCountPerPage(1000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $orderby->addOrder($params["sort"], ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
        $this->view->fornecedores = $this->_fornecedorRepository->getList($this->_empresa_id);
        $this->view->pagamentos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
        $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_empresa_id);


        $this->view->params = $params;
        $this->view->meses = AppUtil::getMeses();
        $data_atual = date("Y-m-d");
        $this->view->data_atual = $data_atual;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();

            $validate = $this->_validate($data);
            $validate .= $this->_validateRecorrencia($data);
            $validate .= $this->_validatePagamento($data);

            $recorrencia = $this->_setRecorrencia($data);
            $lancamento_para = $this->_setLancamentoPara($data);
            $lancamento_temp = $this->_setDataTemp($data);


            if (!isset($validate) || $validate == "") {

                $plano_de_contas = $this->_planodecontasRepository->getById($data['plano_de_contas_id']);
                if ($plano_de_contas->descricao == PlanoDeContas::TRANSFERENCIA) {
                    $this->_transferencia($data);
                } else {
                    $lancamento = $this->_setData($data);
                    $lancamento->save();
                }

                $this->_helper->FlashMessenger('Lançamento cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/lancamento/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->lancamento = $lancamento_temp;
                $this->view->recorrencia_data = $recorrencia;
                $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_empresa_id);
                $this->view->lancamento_para = $lancamento_para;
                $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa_id);

            }
        }
        $this->view->recorrencias = $this->_lancamentoRecorrenciaTipoRepostirory->getListActive($this->_empresa_id);
        $this->view->clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);
        $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
        $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa_id);
        $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_empresa_id);
        $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);

    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $lancamento = $this->_lancamentoRepository->getById($id, $this->_empresa_id);

        if ($lancamento != FALSE) {
            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $validate = $this->_validate($data);
                $validate .= $this->_validatePagamento($data);
                if (!isset($validate) || $validate == "") {
                    if ($data['recorrenia_edicao'] == Lancamento::EDITAR_RECORRENCIA_UM) {
                        $this->_setDataEdit($lancamento, $data);
                        $lancamento->save();
                        $this->_helper->FlashMessenger('Lançamento alterado com sucesso.');
                    } else {
                        $this->_tratarRecorrencia($lancamento, $data);
                        $this->_helper->FlashMessenger('Lançamentos alterados com sucesso.');
                    }
                    $this->_redirect($this->view->baseUrl() . '/admin/lancamento/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->lancamento = $lancamento;
                    $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_empresa_id);
                    $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
                    $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
                    $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa_id);
                    $this->view->lancamento_para = $this->_getLancamentoPara($lancamento->fornecedor_id);
                    $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);

                }
            }
            $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_empresa_id);
            $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
            $this->view->lancamento = $lancamento;
            $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
            $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa_id);
            $this->view->lancamento_para = $this->_getLancamentoPara($lancamento->fornecedor_id);
            $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);

        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando acessar um lançamento que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/lancamento/');
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $lancamento = $this->_lancamentoRepository->getById($id, $this->_empresa_id);

        if ($lancamento != FALSE) {
            $recorrencias = count($this->_lancamentoRepository->getByRecorrencia($lancamento->lancamento_recorrencia_id, $this->_empresa_id));
            $lancamento->delete();

            if ($recorrencias == 1)
                $lancamento->LancamentoRecorrencia->delete();


            $this->_helper->FlashMessenger('Lançamento removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um lançamento que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/lancamento/');
    }

    private function _setData($data) {

        if ($data['recorrencia'] == Lancamento::SIM) {

            $quantidade_ocorrencias = $data['qt_ocorrencias'];

            for ($i = 1; $i <= $quantidade_ocorrencias; $i++) {
                $lancamento = new Lancamento();
                if ($i == 1) {
                    $recorrencia = new LancamentoRecorrencia();
                    $recorrencia->save();
                    $vencimento = AppUtil::convertStringToDate($data['vencimento_data']);
                    $lancamento->vencimento_data = $vencimento;
                } else {
                    $vencimento = $this->_getVencimento($data['frequencia'], $vencimento);
                    $lancamento->vencimento_data = $vencimento;
                }

                $lancamento->lancamento_recorrencia_id = $recorrencia->id;
                $lancamento->descricao = $data['lancamento_descricao'];
                $lancamento->descricao_recorrencia = ' (' . str_pad($i, '2', '0', STR_PAD_LEFT) . '/' . str_pad($quantidade_ocorrencias, '2', '0', STR_PAD_LEFT) . ') ';
                $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
                $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
                $lancamento->tipo = $data['tipo_lancamento'];
                $lancamento->conta_bancaria_id =  $data['conta_bancaria'];
                $lancamento->lancamento_recorrencia_tipo_id = $data['frequencia'];
                $lancamento->empresa_id = $this->_empresa_id;

                $lancamento->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
                $forma_pagamento = $this->_pagamentoTipoRepository->getById($data['forma_pagamento']);
                $lancamento->pagamento_percentual = $forma_pagamento->percentual;
                $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);


                if ($data['lancamento_para'] == Lancamento::CLIENTE) {
                    $lancamento->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
                    $lancamento->fornecedor_id = NULL;
                } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
                    $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
                    $lancamento->cliente_id = NULL;
                }
                $lancamento->save();
            }
        } else {
            $lancamento = new Lancamento();
            $lancamento->descricao = $data['lancamento_descricao'];
            $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            $lancamento->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
            $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
            $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
            $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
            $lancamento->conta_bancaria_id =  $data['conta_bancaria'];
            $lancamento->tipo = $data['tipo_lancamento'];

            $lancamento->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
            $forma_pagamento = $this->_pagamentoTipoRepository->getById($data['forma_pagamento']);
            $lancamento->pagamento_percentual = $forma_pagamento->percentual;
            $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);

            $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
            $plano_de_contas = $this->_planodecontasRepository->getById($lancamento->plano_de_contas_id);
            if ($plano_de_contas->codigo == NULL) {
                $lancamento->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
                $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            }
            if ($data['lancamento_para'] == Lancamento::CLIENTE) {
                $lancamento->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
                $lancamento->fornecedor_id = NULL;
            } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
                $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
                $lancamento->cliente_id = NULL;
            }
            $lancamento->empresa_id = $this->_empresa_id;
        }

        return $lancamento;
    }

    private function _transferencia($data) {

        $lancamento_origem = new Lancamento();
        $lancamento_destino = new Lancamento();

        if ($data['lancamento_para'] == Lancamento::CLIENTE) {
            $lancamento_origem->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
            $lancamento_destino->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
            $lancamento_origem->fornecedor_id = NULL;
            $lancamento_destino->fornecedor_id = NULL;
        } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
            $lancamento_origem->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento_destino->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento_origem->cliente_id = NULL;
            $lancamento_destino->cliente_id = NULL;
        }

        $lancamento_origem->descricao = $data['lancamento_descricao'];
        $lancamento_origem->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_origem->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_origem->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_origem->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_origem->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
        $lancamento_origem->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
        $lancamento_origem->conta_bancaria_id =  $data['conta_bancaria'];
        $lancamento_origem->tipo = Lancamento::DESPESA;
        $lancamento_origem->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento_origem->empresa_id = $this->_empresa_id;
        $lancamento_origem->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
        $lancamento_origem->save();


        $lancamento_destino->descricao = $data['lancamento_descricao'];
        $lancamento_destino->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_destino->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_destino->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_destino->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_destino->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
        $lancamento_destino->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
        $lancamento_destino->conta_bancaria_id =  $data['conta_bancaria'];
        $lancamento_destino->tipo = Lancamento::RECEITA;
        $lancamento_destino->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento_destino->empresa_id = $this->_empresa_id;
        $lancamento_destino->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
        $lancamento_destino->save();
    }

    private function _setDataEdit(Lancamento $lancamento, $data) {

         //$conta_bancaria = $this->_contaBancariaRepository->getById($data['conta_bancaria']);

        $lancamento->descricao = $data['lancamento_descricao'];
        $lancamento->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
        $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
        $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
        $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
        $lancamento->conta_bancaria_id =  $data['conta_bancaria'];
        $lancamento->tipo = $data['tipo_lancamento'];
        $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento->empresa_id = $this->_empresa_id;
        $lancamento->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;


        $plano_de_contas = $this->_planodecontasRepository->getById($lancamento->plano_de_contas_id);

        if ($plano_de_contas->codigo == NULL) {
            $lancamento->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
            $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        }

        if ($data['lancamento_para'] == Lancamento::CLIENTE) {
            $lancamento->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
            $lancamento->fornecedor_id = NULL;
        } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
            $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento->cliente_id = NULL;
        }


    }

    private function _tratarRecorrencia(Lancamento $lancamento, $data) {
        $edicao_recorrencia = $data['recorrenia_edicao'];

        if ($edicao_recorrencia == Lancamento::EDITAR_RECORRENCIA_PROXIMOS)
            $lancamentos_list = $this->_lancamentoRepository->getByRecorrenciaAndEmpresa($lancamento, $this->_empresa_id,1);
        else
            $lancamentos_list = $this->_lancamentoRepository->getByRecorrenciaAndEmpresa($lancamento,$this->_empresa_id);

        $plano_de_contas = $this->_planodecontasRepository->getById($data['plano_de_contas_id']);

        $lancamento->descricao = $data['lancamento_descricao'];
        $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
        $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
        $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
        $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
        $lancamento->tipo = $data['tipo_lancamento'];
        $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento->conta_bancaria_id =  $data['conta_bancaria'];
        $lancamento->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
        $lancamento->empresa_id = $this->_empresa_id;


        if ($plano_de_contas->codigo == NULL) {
            $lancamento->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
            $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        }

        if ($data['lancamento_para'] == Lancamento::CLIENTE) {
            $lancamento->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
            $lancamento->fornecedor_id = NULL;
        } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
            $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento->cliente_id = NULL;
        }
        $lancamento->save();


        foreach ($lancamentos_list as $item) {
            $item->descricao = $data['lancamento_descricao'];
            $item->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            $item->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
            $item->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
            $item->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
            $item->tipo = $data['tipo_lancamento'];
            $item->plano_de_contas_id = $data['plano_de_contas_id'];
            $item->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
            $item->pagamento_percentual = AppUtil::convertStringToFloat($data['pagamento_percentual']);
            $item->conta_bancaria_id =  $data['conta_bancaria'];
            $item->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;

            if ($plano_de_contas->codigo == NULL) {
                $item->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
                $item->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            }

            if ($data['lancamento_para'] == Lancamento::CLIENTE) {
                $item->cliente_id = $data['cliente'] != 0 ? $data['cliente'] : NULL;
                $item->fornecedor_id = NULL;
            } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
                $item->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
                $item->cliente_id = NULL;
            }

            $item->save();
        }
    }

    private function _getVencimento($frequencia, $vencimento) {

        $recorrencia = $this->_lancamentoRecorrenciaTipoRepostirory->getById($frequencia);

        switch ($recorrencia->dias) {

            case 1:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 1 day"));

            case 7:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 7 day"));

            case 15:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 15 day"));

            case 30:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 1 month"));

            case 60:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 2 month"));

            case 90:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 3 month"));

            case 180:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 6 month"));

            case 365:
                return date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($vencimento)) . "+ 1 year"));

            default:
                return 'Indefinido';
        }
    }

    private function _setDataTemp($data) {

        $lancamento->descricao = $data['lancamento_descricao'];
        $lancamento->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento->conta_bancaria_id = $data['conta_bancaria'];
        $lancamento->tipo = $data['tipo_lancamento'];
        $lancamento->cliente_id = $data['cliente'];
        $lancamento->fornecedor_id = $data['fornecedor'];
        $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
        $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
        $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);

        $lancamento->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
        $forma_pagamento = $this->_pagamentoTipoRepository->getById($data['forma_pagamento']);
        $lancamento->pagamento_percentual = $forma_pagamento->percentual;
        $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);

        return $lancamento;
    }

    private function _setRecorrencia($data) {
        $recorrencia = array();
        $recorrencia['recorrencia'] = $data['recorrencia'];
        $recorrencia['frequencia'] = $data['frequencia'];
        $recorrencia['qt_ocorrencias'] = $data['qt_ocorrencias'];

        return $recorrencia;
    }

    private function _setLancamentoPara($data) {
        $tipo = array();
        $tipo['tipo'] = $data['lancamento_para'];
        return $tipo;
    }


    private function _getLancamentoPara($fornecedor_id) {
        $tipo = array();

        $tipo['tipo'] = $fornecedor_id != NULL ? Lancamento::FORNECEDOR : Lancamento::CLIENTE;
        return $tipo;
    }

    private function _validate($lancamento) {

        if ($lancamento['lancamento_descricao'] == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        if ($lancamento['vencimento_data'] == "")
            $result .= "<li>O campo <b>Vencimento</b> deve ser informado.</li>";

        if ($lancamento['tipo_lancamento'] == Lancamento::DESPESA && $lancamento['fornecedor'] <=0 )
            $result .= "<li>O campo <b>Fornecedor</b> deve ser informado.</li>";
/**
        if ($lancamento['tipo'] == Lancamento::CLIENTE  && $lancamento->cliente_id <=0 )
            $result .= "<li>O campo <b>Cliente</b> deve ser informado.</li>";

        if ($lancamento['tipo'] == Lancamento::FORNECEDOR && $lancamento->fornecedor_id <=0 )
            $result .= "<li>O campo <b>Fornecedor</b> deve ser informado.</li>";
 *
 *
 *
        //Inativado pois tem casos exporadicos onde o valor é R$0,00
        /*
        if (AppUtil::convertStringToFloat($lancamento['vencimento_valor']) <= 0)
            $result .= "<li>O campo <b>Valor</b> deve ser informado.</li>";
        */

        if (AppUtil::convertStringToFloat($lancamento['pagamento_desconto']) > AppUtil::convertStringToFloat($lancamento['vencimento_valor']))
            $result .= "<li>O valor de <b>desconto</b> não pode ser maior que o valor do lançamento.</li>";

        if ($lancamento['plano_de_contas_id'] <= 0)
            $result .= "<li>O campo <b>Plano de Contas</b> deve ser informado.</li>";

        return $result;
    }

    private function _validateRecorrencia($recorrencia) {
        if ($recorrencia['recorrencia'] == Lancamento::SIM && $recorrencia['frequencia'] <= 0)
            $result .= "<li>O campo <b>Com que frequêcia</b> deve ser informado.</li>";

        if ($recorrencia['recorrencia'] == Lancamento::SIM && $recorrencia['qt_ocorrencias'] == "")
            $result .= "<li>O campo <b>Término após</b> deve ser informado.</li>";



        return $result;
    }

    private function _validatePagamento($lancamento) {

        if ($lancamento['pagamento_data'] != "" && AppUtil::convertStringToFloat($lancamento['vencimento_valor']) > 0 && AppUtil::convertStringToFloat($lancamento['pagamento_valor']) <= 0)
            $result .= "<li>O campo <b>Valor a Pagar</b> deve ser informado.</li>";

        return $result;
    }

}
