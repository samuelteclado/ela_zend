<?php

class Ajax_LivroGrauRelevanciaController extends Zend_Controller_Action {

    private $_livrograurelevanciaRepository;
    private $_escola_id;

    public function init() {
        $this->_livrograurelevanciaRepository = new LivroGrauRelevanciaRepository();
        $this->_escola_id = SessionUtil::getEscolaSession();
        $this->_helper->layout->disableLayout();
    }

      public function listAction() {
        $relevancias = $this->_livrograurelevanciaRepository->getListByEscola($this->_escola_id);
        
        $relevancia = array();
        $relevancia_list = array();

        foreach ($relevancias as $relevancia_obj):

            $relevancia['id'] = $relevancia_obj->id;
            $relevancia['descricao'] = $relevancia_obj->descricao;
            

            $relevancia_list[] = $relevancia;

        endforeach;

        $this->_helper->json(Zend_Json::encode($relevancia_list));
    
    }

}
