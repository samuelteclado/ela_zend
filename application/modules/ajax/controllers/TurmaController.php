<?php

class Ajax_TurmaController extends Zend_Controller_Action {

    private $_turmaRepository;

    public function init() {
        $this->_turmaRepository = new TurmaRepository();
        $this->_helper->layout->disableLayout();
    }

    public function listAction() {

        $curso_id = $this->getRequest()->getParam('id');

        $turmas = $this->_turmaRepository->getListByCurso($curso_id);

        $turma = array();
        $turma_list = array();

        foreach ($turmas as $turma_obj):
            if (count($turma_obj->Aluno) < $turma_obj->limite_alunos) {
                $turma['id'] = $turma_obj->id;
                $turma['descricao'] = $turma_obj->descricao;

                $turma_list[] = $turma;
            }
        endforeach;
 
        $this->_helper->json(Zend_Json::encode($turma_list));
    }

}
