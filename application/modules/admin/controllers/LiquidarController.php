<?php

class Admin_LiquidarController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_lancamentoRecorrenciaTipoRepostirory;
    private $_planodecontasRepository;
    private $_pagamentoTipoRepository;
    private $_empresa_id;

    public function init() {
        $this->_helper->layout->setLayout('layout_no_menu');
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_planodecontasRepository = new PlanodeContasRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
    }

    public function lancamentoAction(){
        $id = $this->getRequest()->getParam('id');
        $lancamento = $this->_lancamentoRepository->getById($id, $this->_empresa_id);

        if ($lancamento != FALSE) {
            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($lancamento, $data);
                $lancamento->save();
                $this->_helper->FlashMessenger('Lançamento baixado com sucesso.');

                $this->_redirect($this->view->baseUrl() . '/admin/lancamento/');
            }
            $this->view->lancamento = $lancamento;
            $this->view->forma_pagamento = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);

        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando liquidar um lançamento que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/lancamento/');
        }

    }

    private function _setData(Lancamento $lancamento, $data) {
        $lancamento->pagamento_tipo_id = $data['forma_pagamento'];

        $pagamento_tipo = $this->_pagamentoTipoRepository->getById($data['forma_pagamento']);

        $taxa_pagamento = $lancamento->vencimento_valor * ($pagamento_tipo->percentual/100);
        $pagamento_valor = $lancamento->vencimento_valor - $taxa_pagamento;

        $lancamento->pagamento_percentual = $pagamento_tipo->percentual;
        $lancamento->pagamento_taxa = $taxa_pagamento;
        $lancamento->pagamento_valor = $pagamento_valor;
        $lancamento->pagamento_data = date('Y-m-d');




    }
}
