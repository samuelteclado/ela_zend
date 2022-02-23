<?php

class Admin_ProdutoVendaController extends Zend_Controller_Action
{

    private $_clienteRepository;
    private $_movimentoRepository;
    private $_pagamentoTipoRepository;
    private $_empresa;

    public function init() {
        $this->_empresa = SessionUtil::getEmpresaSession();
        $this->_clienteRepository = new ClienteRepository();
        $this->_movimentoRepository = new MovimentoRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();

    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $params['ano'] = ($params['ano']) ? $params['ano'] : date('Y');
        $params['mes'] = ($params['mes']) ? $params['mes'] : date('m');
        $ano = $params['ano'];
        $mes = $params['mes'];
        $ultimo_dia = date("d", mktime(0, 0, 0, ($mes + 1), 0, $ano));

        $filter = new RepositoryFilter($params);
        $filter->addGenericFilter('(data_movimento BETWEEN "' . date("$ano-$mes-01") . '" AND "' . date("$ano-$mes-$ultimo_dia") .'")');
        $filter->addFilter('empresa_id = ?', $this->_empresa);
        $filter->addFilter('tipo = ?', Movimento::VENDA);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addSelectFilter('cliente_id', $params['cliente']);
        $filter->addSelectFilter('pagamento_tipo_id', $params['pagamento']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'data_movimento';

        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_movimentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(1000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $orderby->addOrder($params["sort"], ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa);
        $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);

        $this->view->params = $params;
        $this->view->meses = AppUtil::getMeses();
        $data_atual = date("Y-m-d");
        $this->view->data_atual = $data_atual;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $produto_venda = new Movimento();

            $data = $this->getRequest()->getPost();

            $this->_setData($produto_venda, $data);

            $validate = $this->_validate($produto_venda);
            if (!isset($validate) || $validate == "") {
                $produto_venda->save();

                $this->_helper->FlashMessenger('Compra de produto cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/produto-venda/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->produto = $produto_venda;
            }
        }
        $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa);
        $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);

    }


    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $produto_venda = $this->_movimentoRepository->getById($id);

        if ($produto_venda != FALSE) {

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();

                $this->_setData($produto_venda, $data);

                $validate = $this->_validate($produto_venda);
                if (!isset($validate) || $validate == "") {
                    $produto_venda->save();

                    $this->_helper->FlashMessenger('Venda alterada com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/produto-venda/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->produto = $produto_venda;
                    $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa);
                    $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);

                }
            } else {
                $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa);
                $this->view->produto = $produto_venda;
                $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);

            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando alterar uma venda que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/produto-venda/');
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $movimento = $this->_movimentoRepository->getById($id);

        $movimento->status = Movimento::EXCLUIDO;
        $movimento->save();

        // $this->_empresaRepository->remove($id);
        $this->_helper->FlashMessenger('Venda removida com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/produto-venda/');
    }

    private function _setData(Movimento $venda, $data) {

        $venda->tipo = $data['tipo'];
        $venda->data_movimento = AppUtil::convertStringToDate($data['data_compra']);
        $venda->valor = AppUtil::convertStringToFloat($data['valor']);
        $venda->status = $data['status'];
        $venda->observacao = $data['observacao'];
        $venda->cliente_id = $data['cliente'];
        $venda->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
        $venda->empresa_id = $this->_empresa;

        if ($venda->Lancamento[0]->id <= 0) {
            $movimento_lancamento = new Lancamento();
            $this->_setDataLancamento($movimento_lancamento, $data);
            $venda->Lancamento[0] = $movimento_lancamento;
        } else {
            $movimento_lancamento = $venda->Lancamento[0];
            $this->_setDataLancamento($movimento_lancamento, $data);
        }
    }

    private function _setDataLancamento(Lancamento $movimento_lancamento, $data){


        $valor = AppUtil::convertStringToFloat($data['valor']);
        $movimento_lancamento->descricao = "Venda de Produtos";

        //$plano_de_contas = $this->_planodeContasRepository->getByDescricao($this->_empresa,$procedimentoTipo->getTipo());
        //$movimento_lancamento->plano_de_contas_id = $plano_de_contas->id;

        if($data['forma_pagamento'] != 0){
            $pagamentoTipo = $this->_pagamentoTipoRepository->getById($data['forma_pagamento']);
            $movimento_lancamento->pagamento_valor = $valor - ($valor * ($pagamentoTipo->percentual/100));
            $movimento_lancamento->pagamento_taxa = $valor * ($pagamentoTipo->percentual/100);
            $movimento_lancamento->pagamento_percentual = $pagamentoTipo->percentual;
            $movimento_lancamento->pagamento_tipo_id = $data['forma_pagamento'];
        }
        $movimento_lancamento->status = Movimento::ABERTO;
        if($data['status'] == Movimento::CANCELADO)
            $movimento_lancamento->status = Lancamento::EXCLUIDO;


        $movimento_lancamento->tipo = Lancamento::RECEITA;
        $movimento_lancamento->vencimento_data = AppUtil::convertStringToDate($data['data_compra']);
        $movimento_lancamento->vencimento_valor = $valor;

        $movimento_lancamento->cliente_id = $data['cliente'];
        $movimento_lancamento->empresa_id = $this->_empresa;


    }

    private function _validate(Movimento $venda) {
        if ($venda->cliente_id <= 0)
            $result .= "<li>O campo <b>Cliente </b> deve ser informado.</li>";

        if ($venda->data_movimento == "")
            $result .= "<li>O campo <b>Data Compra</b> deve ser informado.</li>";

        if ($venda->status == Movimento::REALIZADO && $venda->pagamento_tipo_id <= 0)
            $result .= "<li>O campo <b>Forma de Pagamento</b> deve ser informado.</li>";


        return $result;
    }


}