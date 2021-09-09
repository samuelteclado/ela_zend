<?php

class Ajax_AvaliacaoController extends Zend_Controller_Action {

    private $_cursoAvaliacaoNotaRepository;

    public function init() {
        $this->_cursoAvaliacaoNotaRepository = new CursoAvaliacaoRepository();
        $this->_helper->layout->disableLayout();
    }

    public function listAction() {

        $disciplina_id = $this->getRequest()->getParam('disciplina');
        $turma_id = $this->getRequest()->getParam('turma');

        $avaliacoes = $this->_cursoAvaliacaoNotaRepository->getListByTurma($turma_id);
        $avaliacoes_lancadas = $this->_cursoAvaliacaoNotaRepository->getListByTurmaAndDisciplina($turma_id, $disciplina_id);

        $avaliacao = array();
        $avaliacao_list = array();

        foreach ($avaliacoes as $avaliacao_obj):
            $a = TRUE;
            foreach ($avaliacoes_lancadas as $avaliacao_lancada_obj) {
                if ($avaliacao_obj->id == $avaliacao_lancada_obj->id) {
                    $a = FALSE;
                    break;
                }
            }

            if ($a) {
                $avaliacao['id'] = $avaliacao_obj->id;
                $avaliacao['descricao'] = $avaliacao_obj->descricao;
                $avaliacao['pontos'] = $avaliacao_obj->pontos;
                $avaliacao_list[] = $avaliacao;
            }

        endforeach;


        $this->_helper->json(Zend_Json::encode($avaliacao_list));
    }

}
