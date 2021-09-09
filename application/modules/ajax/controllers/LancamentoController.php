<?php

class Ajax_LancamentoController extends Zend_Controller_Action {

    private $_lancamentoRepository;

    public function init() {
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_helper->layout->disableLayout();
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
