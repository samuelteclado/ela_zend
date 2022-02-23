<?php

class Admin_ProdutoCompraController extends Zend_Controller_Action
{

    private $_fornecedorRepository;
    private $_movimentoRepository;
    private $_movimentoItemRepository;
    private $_pagamentoTipoRepository;
    private $_produtoRepository;
    private $_empresa;

    public function init() {
        $this->_empresa = SessionUtil::getEmpresaSession();
        $this->_fornecedorRepository = new FornecedorRepository();
        $this->_movimentoRepository = new MovimentoRepository();
        $this->_movimentoItemRepository = new MovimentoItemRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
        $this->_produtoRepository = new ProdutoRepository();

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
        $filter->addFilter('tipo = ?', Movimento::COMPRA);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addSelectFilter('fornecedor_id', $params['fornecedor']);
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
        $this->view->fornecedores = $this->_fornecedorRepository->getList($this->_empresa);
        $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);

        $this->view->params = $params;
        $this->view->meses = AppUtil::getMeses();
        $data_atual = date("Y-m-d");
        $this->view->data_atual = $data_atual;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $produto_compra = new Movimento();

            $data = $this->getRequest()->getPost();

            $this->_setData($produto_compra, $data);

            $validate = $this->_validate($produto_compra);
            if (!isset($validate) || $validate == "") {
                $produto_compra->save();

                $this->_helper->FlashMessenger('Compra de produto cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/produto-compra/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->produto_compra = $produto_compra;
            }
        }
        $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa);
        $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);
        $this->view->produtos = $this->_produtoRepository->getListByEmpresa($this->_empresa);

    }


    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $produto_compra = $this->_movimentoRepository->getById($id);

        if ($produto_compra != FALSE) {


            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();

                $this->_setData($produto_compra, $data);

                $validate = $this->_validate($produto_compra);
                if (!isset($validate) || $validate == "") {
                    $produto_compra->save();

                    $this->_helper->FlashMessenger('Compra alterada com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/produto-compra/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->produto_compra = $produto_compra;
                    $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa);
                    $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);
                    $this->view->produtos = $this->_produtoRepository->getListByEmpresa($this->_empresa);


                }
            } else {
                $this->view->fornecedores = $this->_fornecedorRepository->getListByEmpresa($this->_empresa);
                $this->view->produto_compra = $produto_compra;
                $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa);
                $this->view->produtos = $this->_produtoRepository->getListByEmpresa($this->_empresa);

            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando alterar uma compra que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/produto-compra/');
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $produto_compra = $this->_movimentoRepository->getById($id);


        foreach ($produto_compra->MovimentoItem as $movimento_item) {
            $movimento_item->delete();
        }
        $produto_compra->status = Movimento::EXCLUIDO;
        $produto_compra->save();

        $this->_helper->FlashMessenger('Compra removida com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/produto-compra/');
    }

    private function _setData(Movimento $compra, $data) {

        $compra->tipo = $data['tipo'];
        $compra->data_movimento = AppUtil::convertStringToDate($data['data_compra']);
        $compra->valor = AppUtil::convertStringToFloat($data['valor']);
        $compra->status = $data['status'];
        $compra->observacao = $data['observacao'];
        $compra->fornecedor_id = $data['fornecedor'];
        $compra->pagamento_tipo_id = $data['forma_pagamento'] != 0 ? $data['forma_pagamento'] : NULL;
        $compra->empresa_id = $this->_empresa;

        $this->_setDataItens($compra, $data);

       if ($compra->Lancamento[0]->id <= 0) {
            $movimento_lancamento = new Lancamento();
            $this->_setDataLancamento($movimento_lancamento, $data);
            $compra->Lancamento[0] = $movimento_lancamento;
        } else {
            $movimento_lancamento = $compra->Lancamento[0];
            $this->_setDataLancamento($movimento_lancamento, $data);
        }
    }


    private function _setDataItens(Movimento $compra, $data) {

        $arr_compra_item_produto_id = $data['produto_item_id'];
        $arr_compra_item_valor = $data['qt_valor'];
        $arr_compra_item_quantidade = $data['qt_produto'];
        $arr_compra_item_valor_total = $data['valor_total'];

        $compra->MovimentoItem->clear();

        for ($index = 1; $index < count($arr_compra_item_produto_id); $index++) {

            $produto_item = new MovimentoItem();
            $produto_item->produto_id = $arr_compra_item_produto_id[$index];
            $produto_item->valor = AppUtil::convertStringToFloat($arr_compra_item_valor[$index]);
            $produto_item->quantidade = AppUtil::convertStringToFloat($arr_compra_item_quantidade[$index]);
            $produto_item->valor_total = AppUtil::convertStringToFloat($arr_compra_item_valor_total[$index]);

            $compra->MovimentoItem->add($produto_item);
        }
    }
    private function _setDataLancamento(Lancamento $movimento_lancamento, $data){


        $fornecedor_lancamento = $this->_fornecedorRepository->getById($data['fornecedor']);
        $valor = AppUtil::convertStringToFloat($data['valor']);
        $movimento_lancamento->descricao = $fornecedor_lancamento->descricao ." - Compra de Produtos";

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


        $movimento_lancamento->tipo = Lancamento::DESPESA;
        $movimento_lancamento->vencimento_data = AppUtil::convertStringToDate($data['data_compra']);
        $movimento_lancamento->vencimento_valor = $valor;


        $movimento_lancamento->fornecedor_id = $data['fornecedor'];
        $movimento_lancamento->empresa_id = $this->_empresa;


    }

    private function _validate(Movimento $compra) {
        if ($compra->fornecedor_id <= 0)
            $result .= "<li>O campo <b>Fornecedor </b> deve ser informado.</li>";

        if ($compra->data_movimento == "")
            $result .= "<li>O campo <b>Data Compra</b> deve ser informado.</li>";

        if ($compra->status == Movimento::REALIZADO && $compra->pagamento_tipo_id <= 0)
            $result .= "<li>O campo <b>Forma de Pagamento</b> deve ser informado.</li>";


        return $result;
    }


}