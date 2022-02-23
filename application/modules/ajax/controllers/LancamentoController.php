<?php

class Ajax_LancamentoController extends Zend_Controller_Action {

    private $_lancamentoRepository;
    private $_pagamentoTipoRepository;

    public function init() {
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
        $this->_helper->layout->disableLayout();
    }

    public function liquidarAction() {

        $pagamento_tipo = $this->getRequest()->getParam('pagamentoTipo');
        $lancamento_id = $this->getRequest()->getParam('lancamento');


        $pagamento_tipo_obj = $this->_pagamentoTipoRepository->getById($pagamento_tipo);
        $lancamento_obj = $this->_lancamentoRepository->getById($lancamento_id);

        $taxa_pagamento = $lancamento_obj->vencimento_valor*($pagamento_tipo_obj->percentual/100);
        $pagamento_valor = $lancamento_obj->vencimento_valor - $taxa_pagamento;

        $lancamento = array();
        $lancamento['id'] = $pagamento_tipo_obj->id;
        $lancamento['percentual'] = AppUtil::convertFloatToString($pagamento_tipo_obj->percentual);
        $lancamento['pagamento_taxa'] = AppUtil::convertFloatToString($taxa_pagamento);
        $lancamento['pagamento_valor'] = AppUtil::convertFloatToString($pagamento_valor);

        $this->_helper->json(Zend_Json::encode($lancamento));
    }


    public function calculoAction() {

        $pagamento_data = $this->getRequest()->getParam('pagamento_data');
        $id_lancamento = $this->getRequest()->getParam('id_lancamento');
        
        $lancamento = $this->_lancamentoRepository->getById($id_lancamento);
               
        $dias_atraso = AppUtil::getDiferecaDias($lancamento->vencimento_data, AppUtil::convertStringToDate($pagamento_data));
        
        $valor_calculado = array();
        
        $valor_calculado['valor_total'] = AppUtil::convertFloatToString($lancamento->vencimento_valor);
        
        if($dias_atraso > 0) {
        
        $valor = $lancamento->vencimento_valor;
        $multa = ($lancamento->vencimento_multa / 100) * $valor ;
        $juros = ((($lancamento->vencimento_juros / 100) * $dias_atraso ) / 30) * $valor;
        
        $valor_calculado['valor'] = AppUtil::convertFloatToString($valor);
        $valor_calculado['multa'] = AppUtil::convertFloatToString($multa);
        $valor_calculado['juros'] = AppUtil::convertFloatToString($juros);
        $valor_calculado['valor_total'] = AppUtil::convertFloatToString($valor + $multa + $juros);

        }       

        $this->_helper->json(Zend_Json::encode($valor_calculado));
    }

}
