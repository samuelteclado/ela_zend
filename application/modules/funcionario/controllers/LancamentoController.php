<?php

class Funcionario_LancamentoController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_contaBancariaRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_planodecontasRepository;
    private $_fornecedorRepository;
    private $_escola;

    public function init() {
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_lancamentoRecorrenciaTipoRepostirory = new LancamentoRecorrenciaTipoRepository();
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
        $filter->addFilter('escola_id = ?', $this->_escola);
        $filter->addFilter('tipo != ?', Lancamento::MENSALIDADE);
        $filter->addSelectFilter('cliente_id', $params['cliente']);
        $filter->addDateFilter('data', $params['data']);
        $filter->addLeftJoinFilter("p.Aluno a");
        if ($params['situacao'] == Lancamento::ABERTO) {
            $filter->addNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::LIQUIDADO) {
            $filter->addNotNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::VENCIDO) {
            $filter->addGenericFilter('(vencimento_data < "' . date('Y-m-d') . '" AND vencimento_data_atualizada IS NULL ) OR (vencimento_data_atualizada < "' . date('Y-m-d') . '")');
            $filter->addNullFilter('pagamento_data');
        }


        $sortParam = ($params["sort"]) ? $params["sort"] : 'vencimento_data';
        $sortParam = ($sortParam == "vencimento_data") ? 'CASE WHEN vencimento_data_atualizada IS NULL THEN vencimento_data ELSE vencimento_data_atualizada END' : $sortParam;

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
        $this->view->alunos = $this->_alunoRepository->getListAll($this->_escola);
        $this->view->fornecedores = $this->_fornecedorRepository->getList($this->_escola);

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
                $this->_redirect($this->view->baseUrl() . '/financeiro/lancamento/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->lancamento = $lancamento_temp;
                $this->view->recorrencia_data = $recorrencia;
                $this->view->lancamento_para = $lancamento_para;
            }
        }
        $this->view->recorrencias = $this->_lancamentoRecorrenciaTipoRepostirory->getListActive();
        $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_escola);
        $this->view->alunos = $this->_alunoRepository->getListActive($this->_escola);
        $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
        $this->view->fornecedores = $this->_fornecedorRepository->getList($this->_escola);
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $lancamento = $this->_lancamentoRepository->getById($id, $this->_escola);

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
                    $this->_redirect($this->view->baseUrl() . '/financeiro/lancamento/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->lancamento = $lancamento;
                }
            }
            $this->view->alunos = $this->_alunoRepository->getListActive($this->_escola, NULL, $lancamento->aluno_id);
            $this->view->lancamento = $lancamento;
            $this->view->contas = $this->_contaBancariaRepository->getListActive($this->_escola);
            $this->view->planos_de_contas = $this->_planodecontasRepository->getPlanoDeContas();
            $this->view->fornecedores = $this->_fornecedorRepository->getList($this->_escola);
            $this->view->lancamento_para = $this->_getLancamentoPara($lancamento->fornecedor_id);
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando acessar um lançamento que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/financeiro/lancamento/');
        }
    }

    public function boletoAction() {
        $id = $this->_getBoletoNumero();

        $ch = curl_init($this->view->baseUrl() . '/ajax/boleto/gerar/' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'apiKey=' . EncryptionUtil::getInstance()->encode(Zend_Auth::getInstance()->getIdentity()->id));

        $output = curl_exec($ch);

        echo '<div style="page-break-after: always">';
        echo $output;
        echo '</div>';

        echo '<script type="text/javascript">';
        echo 'window.print();';
        echo '</script>';

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function boletosAction() {
        $boleto_numero = $this->getRequest()->getParam('boleto_numero');
        $this->view->boleto_numero = json_encode($boleto_numero);
        $this->_helper->layout()->disableLayout();
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $lancamento = $this->_lancamentoRepository->getById($id, $this->_escola);

        if ($lancamento != FALSE) {
            $lancamento->Boleto->delete();
            $lancamento->NotaFiscal->delete();
            $recorrencias = count($this->_lancamentoRepository->getByRecorrencia($lancamento->lancamento_recorrencia_id));
            $lancamento->delete();

            if ($recorrencias == 1)
                $lancamento->LancamentoRecorrencia->delete();


            $this->_helper->FlashMessenger('Lançamento removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um lançamento que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/financeiro/lancamento/');
    }

    private function _setData($data) {

        $conta_bancaria = $this->_contaBancariaRepository->getById($data['conta_bancaria']);
        $plano_de_contas = $this->_planodecontasRepository->getById($data['plano_de_contas_id']);

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
                $lancamento->vencimento_juros = $conta_bancaria->juros;
                $lancamento->vencimento_multa = $conta_bancaria->multa;
                $lancamento->conta_bancaria_id = $conta_bancaria->id;
                $lancamento->tipo = $data['tipo_lancamento'];
                $lancamento->lancamento_recorrencia_tipo_id = $data['frequencia'];
                $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
                $lancamento->escola_id = $this->_escola;
                if ($data['lancamento_para'] == Lancamento::ALUNO) {
                    $lancamento->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
                    $lancamento->visivel_aluno = $data['visivel'];
                    $lancamento->fornecedor_id = NULL;
                } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
                    $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
                    $lancamento->visivel_aluno = Lancamento::NAO;
                    $lancamento->aluno_id = NULL;
                }
                $lancamento->save();
            }
        } else {
            $lancamento = new Lancamento();
            $lancamento->descricao = $data['lancamento_descricao'];
            $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            $lancamento->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
            $lancamento->vencimento_juros = $conta_bancaria->juros;
            $lancamento->vencimento_multa = $conta_bancaria->multa;
            $lancamento->conta_bancaria_id = $conta_bancaria->id;
            $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
            $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
            $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
            $lancamento->pagamento_multa = AppUtil::convertStringToFloat($data['pagamento_multa']);
            $lancamento->pagamento_juros = AppUtil::convertStringToFloat($data['pagamento_juros']);
            $lancamento->tipo = $data['tipo_lancamento'];

            $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
            $plano_de_contas = $this->_planodecontasRepository->getById($lancamento->plano_de_contas_id);
            if ($plano_de_contas->codigo == NULL) {
                $lancamento->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
                $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            }
            if ($data['lancamento_para'] == Lancamento::ALUNO) {
                $lancamento->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
                $lancamento->visivel_aluno = $data['visivel'];
                $lancamento->fornecedor_id = NULL;
            } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
                $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
                $lancamento->visivel_aluno = Lancamento::NAO;
                $lancamento->aluno_id = NULL;
            }
            $lancamento->escola_id = $this->_escola;

            if ($lancamento->NotaFiscal[0]->id <= 0) {
                $nota_fiscal = new NotaFiscal();
                $this->_setDataNotaFiscal($nota_fiscal, $data);
                $lancamento->NotaFiscal[0] = $nota_fiscal;
            } else {
                $nota_fiscal = $lancamento->NotaFiscal[0];
                $this->_setDataNotaFiscal($nota_fiscal, $data);
            }
        }

        return $lancamento;
    }

    private function _transferencia($data) {

        $lancamento_origem = new Lancamento();
        $lancamento_destino = new Lancamento();

        if ($data['lancamento_para'] == Lancamento::ALUNO) {
            $lancamento_origem->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
            $lancamento_destino->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
            $lancamento_origem->visivel_aluno = $data['visivel'];
            $lancamento_destino->visivel_aluno = $data['visivel'];
            $lancamento_origem->fornecedor_id = NULL;
            $lancamento_destino->fornecedor_id = NULL;
        } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
            $lancamento_origem->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento_destino->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento_destino->visivel_aluno = Lancamento::NAO;
            $lancamento_origem->visivel_aluno = Lancamento::NAO;
            $lancamento_origem->aluno_id = NULL;
            $lancamento_destino->aluno_id = NULL;
        }

        $lancamento_origem->descricao = $data['lancamento_descricao'];
        $lancamento_origem->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_origem->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_origem->conta_bancaria_id = $data['conta_origem'];
        $lancamento_origem->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_origem->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_origem->tipo = Lancamento::DESPESA;
        $lancamento_origem->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento_origem->escola_id = $this->_escola;
        $lancamento_origem->save();


        $lancamento_destino->descricao = $data['lancamento_descricao'];
        $lancamento_destino->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_destino->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_destino->conta_bancaria_id = $data['conta_destino'];
        $lancamento_destino->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento_destino->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento_destino->tipo = Lancamento::RECEITA;
        $lancamento_destino->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento_destino->escola_id = $this->_escola;
        $lancamento_destino->save();
    }

    private function _setDataEdit(Lancamento $lancamento, $data) {

        $conta_bancaria = $this->_contaBancariaRepository->getById($data['conta_bancaria']);

        $lancamento->descricao = $data['lancamento_descricao'];
        $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento->vencimento_data = AppUtil::convertStringToDate($data['vencimento_data']);
        $lancamento->vencimento_juros = $conta_bancaria->juros;
        $lancamento->vencimento_multa = $conta_bancaria->multa;
        $lancamento->conta_bancaria_id = $conta_bancaria->id;
        $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
        $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
        $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
        $lancamento->pagamento_multa = AppUtil::convertStringToFloat($data['pagamento_multa']);
        $lancamento->pagamento_juros = AppUtil::convertStringToFloat($data['pagamento_juros']);
        $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
        $lancamento->tipo = $data['tipo_lancamento'];

        $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
        $plano_de_contas = $this->_planodecontasRepository->getById($lancamento->plano_de_contas_id);
        if ($plano_de_contas->codigo == NULL) {
            $lancamento->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
            $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        }

        if ($data['lancamento_para'] == Lancamento::ALUNO) {
            $lancamento->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
            $lancamento->visivel_aluno = $data['visivel'];
            $lancamento->fornecedor_id = NULL;
        } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
            $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento->visivel_aluno = Lancamento::NAO;
            $lancamento->aluno_id = NULL;
        }

        if ($lancamento->NotaFiscal[0]->id <= 0) {
            $nota_fiscal = new NotaFiscal();
            $this->_setDataNotaFiscal($nota_fiscal, $data);
            $lancamento->NotaFiscal[0] = $nota_fiscal;
        } else {
            $nota_fiscal = $lancamento->NotaFiscal[0];
            $this->_setDataNotaFiscal($nota_fiscal, $data);
        }
    }

    private function _tratarRecorrencia(Lancamento $lancamento, $data) {
        $edicao_recorrencia = $data['recorrenia_edicao'];
        $baixados = $data['baixados'];
        $baixados = ($baixados == NULL ? false : true);

        if ($edicao_recorrencia == Lancamento::EDITAR_RECORRENCIA_PROXIMOS)
            $lancamentos_list = $this->_lancamentoRepository->getByRecorrenciaAndAndEscola($lancamento, $baixados, $this->_escola,1);
        else
            $lancamentos_list = $this->_lancamentoRepository->getByRecorrenciaAndAndEscola($lancamento, $baixados,$this->_escola);

        $plano_de_contas = $this->_planodecontasRepository->getById($data['plano_de_contas_id']);
        $conta_bancaria = $this->_contaBancariaRepository->getById($data['conta_bancaria']);


        $lancamento->descricao = $data['lancamento_descricao'];
        $lancamento->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        $lancamento->vencimento_juros = $conta_bancaria->juros;
        $lancamento->vencimento_multa = $conta_bancaria->multa;
        $lancamento->conta_bancaria_id = $conta_bancaria->id;
        $lancamento->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
        $lancamento->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
        $lancamento->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
        $lancamento->pagamento_multa = AppUtil::convertStringToFloat($data['pagamento_multa']);
        $lancamento->pagamento_juros = AppUtil::convertStringToFloat($data['pagamento_juros']);
        $lancamento->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
        $lancamento->tipo = $data['tipo_lancamento'];
        $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];

        if ($plano_de_contas->codigo == NULL) {
            $item->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
            $item->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
        }

        if ($data['lancamento_para'] == Lancamento::ALUNO) {
            $lancamento->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
            $lancamento->visivel_aluno = $data['visivel'];
            $lancamento->fornecedor_id = NULL;
        } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
            $lancamento->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
            $lancamento->visivel_aluno = Lancamento::NAO;
            $lancamento->aluno_id = NULL;
        }
        $lancamento->save();

        //Atualmente não está sendo utilizado NF
        /*
          if ($lancamento->NotaFiscal[0]->id <= 0) {
          $nota_fiscal = new NotaFiscal();
          $this->_setDataNotaFiscal($nota_fiscal, $data);
          $lancamento->NotaFiscal[0] = $nota_fiscal;
          } else {
          $nota_fiscal = $lancamento->NotaFiscal[0];
          $this->_setDataNotaFiscal($nota_fiscal, $data);
          }
         */


        foreach ($lancamentos_list as $item) {
            $item->descricao = $data['lancamento_descricao'];
            $item->vencimento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            $item->vencimento_juros = $conta_bancaria->juros;
            $item->vencimento_multa = $conta_bancaria->multa;
            $item->conta_bancaria_id = $conta_bancaria->id;
            //$item->pagamento_data = AppUtil::convertStringToDate($data['pagamento_data']);
            //$item->pagamento_valor = AppUtil::convertStringToFloat($data['pagamento_valor']);
            //$item->pagamento_desconto = AppUtil::convertStringToFloat($data['pagamento_desconto']);
            //$item->pagamento_multa = AppUtil::convertStringToFloat($data['pagamento_multa']);
            //$item->pagamento_juros = AppUtil::convertStringToFloat($data['pagamento_juros']);
            //$item->pagamento_taxa = AppUtil::convertStringToFloat($data['pagamento_taxa']);
            $item->tipo = $data['tipo_lancamento'];
            $item->plano_de_contas_id = $data['plano_de_contas_id'];

            if ($plano_de_contas->codigo == NULL) {
                $item->pagamento_data = AppUtil::convertStringToDate($data['vencimento_data']);
                $item->pagamento_valor = AppUtil::convertStringToFloat($data['vencimento_valor']);
            }

            if ($data['lancamento_para'] == Lancamento::ALUNO) {
                $item->aluno_id = $data['aluno'] != 0 ? $data['aluno'] : NULL;
                $item->visivel_aluno = $data['visivel'];
                $item->fornecedor_id = NULL;
            } elseif ($data['lancamento_para'] == Lancamento::FORNECEDOR) {
                $item->fornecedor_id = $data['fornecedor'] != 0 ? $data['fornecedor'] : NULL;
                $item->visivel_aluno = Lancamento::NAO;
                $item->aluno_id = NULL;
            }

            //Atualmente não está sendo utilizado NF
            /*
              if ($item->NotaFiscal[0]->id <= 0) {
              $nota_fiscal = new NotaFiscal();
              $this->_setDataNotaFiscal($nota_fiscal, $data);
              $item->NotaFiscal[0] = $nota_fiscal;
              } else {
              $nota_fiscal = $item->NotaFiscal[0];
              $this->_setDataNotaFiscal($nota_fiscal, $data);
              }
             */


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
        $lancamento->vencimento_data = $data['vencimento_data'];
        $lancamento->vencimento_valor = $data['vencimento_valor'];
        $lancamento->plano_de_contas_id = $data['plano_de_contas_id'];
        $lancamento->conta_bancaria_id = $data['conta_bancaria'];
        $lancamento->aluno_id = $data['aluno'];
        $lancamento->fornecedor_id = $data['fornecedor'];
        $lancamento->pagamento_data = $data['pagamento_data'];
        $lancamento->pagamento_valor = $data['pagamento_valor'];
        $lancamento->pagamento_desconto = $data['pagamento_desconto'];
        $lancamento->pagamento_juros = $data['pagamento_juros'];
        $lancamento->pagamento_multa = $data['pagamento_multa'];
        $lancamento->tipo = $data['tipo_lancamento'];
        $lancamento->visivel_aluno = $data['visivel'];
        $lancamento->conta_origem = $data['conta_origem'];
        $lancamento->conta_destino = $data['conta_destino'];
        $lancamento->NotaFiscal[0]->data_emissao = $data['nf_data_emissao'];
        $lancamento->NotaFiscal[0]->numero_doc = $data['nf_numero_doc'];
        $lancamento->NotaFiscal[0]->observacao = $data['nf_observacao'];
        return $lancamento;
    }

    private function _setDataNotaFiscal(NotaFiscal $nota_fiscal, $data) {
        $nota_fiscal->data_emissao = AppUtil::convertStringToDate($data['nf_data_emissao']);
        $nota_fiscal->numero_doc = $data['nf_numero_doc'];
        $nota_fiscal->observacao = $data['nf_observacao'];
        return $nota_fiscal;
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

        $tipo['tipo'] = $fornecedor_id != NULL ? Lancamento::FORNECEDOR : Lancamento::ALUNO;
        return $tipo;
    }

    private function _validate($lancamento) {

        if ($lancamento['lancamento_descricao'] == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        if (AppUtil::convertStringToFloat($lancamento['vencimento_valor']) <= 0)
            $result .= "<li>O campo <b>Valor</b> deve ser informado.</li>";

        if ($lancamento['vencimento_data'] == "")
            $result .= "<li>O campo <b>Vencimento</b> deve ser informado.</li>";


        if (AppUtil::convertStringToFloat($lancamento['pagamento_desconto']) > AppUtil::convertStringToFloat($lancamento['vencimento_valor']))
            $result .= "<li>O valor de <b>desconto</b> não pode ser maior que o valor do lançamento.</li>";

        if ($lancamento['plano_de_contas_id'] <= 0)
            $result .= "<li>O campo <b>Plano de Contas</b> deve ser informado.</li>";

        $plano_de_contas = $this->_planodecontasRepository->getById($lancamento['plano_de_contas_id']);
        if ($plano_de_contas->descricao != PlanoDeContas::TRANSFERENCIA && $lancamento['conta_bancaria'] <= 0)
            $result .= "<li>O campo <b>Conta</b> deve ser informado.</li>";

        if ($plano_de_contas->descricao == PlanoDeContas::TRANSFERENCIA && $lancamento['conta_origem'] <= 0)
            $result .= "<li>O campo <b>Conta Origem</b> deve ser informado.</li>";

        if ($plano_de_contas->descricao == PlanoDeContas::TRANSFERENCIA && $lancamento['conta_destino'] <= 0)
            $result .= "<li>O campo <b>Conta Destino</b> deve ser informado.</li>";

        return $result;
    }

    private function _validateRecorrencia($recorrencia) {
        if ($recorrencia['recorrencia'] == Lancamento::SIM && $recorrencia['frequencia'] <= 0)
            $result .= "<li>O campo <b>Com que frequêcia</b> deve ser informado.</li>";

        if ($recorrencia['recorrencia'] == Lancamento::SIM && $recorrencia['qt_ocorrencias'] == "")
            $result .= "<li>O campo <b>Término após</b> deve ser informado.</li>";

        return $result;
    }

    private function _validatePagamento($pagamento) {
        if ($pagamento['pagamento_data'] != "" && $pagamento['pagamento_valor'] <= 0)
            $result .= "<li>O campo <b>Valor Pago</b> deve ser informado.</li>";

        if ($pagamento['pagamento_valor'] > 0 && $pagamento['pagamento_data'] == "")
            $result .= "<li>O campo <b>Data Pagamento</b> deve ser informado.</li>";

        return $result;
    }

    public function atualizarAction() {

        $lancamento = $this->_getLancamento();
        if ($lancamento != FALSE) {
            if ($lancamento->id <= 0)
                die;

            $cedente = $lancamento->ContaBancaria;
            if ($cedente->id <= 0)
                die;

            $data = date("Y-m-d");
            $data_atualizada = date("Y-m-d", strtotime(date("Y-m-d H:i:s", strtotime($data)) . "+1 day"));

            $vencimento_valor = $lancamento->vencimento_valor;
            $valor_multa = AppUtil::getValorMulta($lancamento->vencimento_data, $data_atualizada, $cedente->multa, $vencimento_valor);
            $valor_juros = AppUtil::getValorJuros($lancamento->vencimento_data, $data_atualizada, $cedente->juros, $vencimento_valor);
            $valor_vencimento_atualizado = $vencimento_valor + $valor_multa + $valor_juros;

            $lancamento->vencimento_data_atualizada = $data_atualizada;
            $lancamento->vencimento_valor_atualizado = $valor_vencimento_atualizado;

            $lancamento->save();
            $this->_helper->FlashMessenger(array('info' => 'Boleto atualizado com sucesso.'));
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando atualizar um boleto que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/financeiro/lancamento/');
    }

    private function _getBoletoNumero() {
        $boleto_numero = NULL;

        $requestUri = explode('/', $this->getRequest()->getRequestUri());

        foreach ($requestUri as $key => $requestParam) {
            if ($requestParam == 'visualizar' || $requestParam == 'atualizar' || $requestParam == 'boleto') {
                $boleto_numero = $requestUri[$key + 1];
                break;
            }
        }

        return $boleto_numero;
    }

    private function _getLancamento() {
        $boleto_numero = $this->_getBoletoNumero();

        return $this->_lancamentoRepository->getById($boleto_numero, $this->_escola);
    }

}
