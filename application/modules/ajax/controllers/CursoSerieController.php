<?php

class Ajax_CursoSerieController extends Zend_Controller_Action {

    private $_cursoSerieRepository;

    public function init() {
        $this->_cursoSerieRepository = new CursoSerieRepository();
        $this->_helper->layout->setLayout('administrador_no_menu');
    }

    public function listAction() {

        $curso_id = $this->getRequest()->getParam('curso_id');

        $series = $this->_cursoSerieRepository->getSeriesByCurso($curso_id);
      
        
        $serie_obj = array();
        $series_list = array();

        foreach ($series as $serie):

            $serie_obj['id'] = $serie->id;
            $serie_obj['descricao'] = $serie->descricao;
            
            $series_list[] = $serie_obj;

        endforeach;

        $this->_helper->json(Zend_Json::encode($series_list));
    }


}
