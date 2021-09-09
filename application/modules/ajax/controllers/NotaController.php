<?php

class Ajax_NotaController extends Zend_Controller_Action {

    private $_alunoRepository;
    private $_alunoNotaRepository;
    private $_alunoNotaItemRepository;
    private $_escola_id;

    public function init() {
        $this->_alunoRepository = new AlunoRepository();
        $this->_alunoNotaRepository = new AlunoNotaRepository();
        $this->_alunoNotaItemRepository = new AlunoNotaItemRepository();
        $this->_escola_id = SessionUtil::getEscolaSession();
        $this->_helper->layout->disableLayout();
    }

    public function listAction() {

        $turma_id = $this->getRequest()->getParam('id');
        $disciplina_id = $this->getRequest()->getParam('disciplina');
        $curso_avaliacao_id = $this->getRequest()->getParam('avaliacao');
        

        $aluno_nota = $this->_alunoNotaRepository->getAlunoNota($turma_id, $disciplina_id, $curso_avaliacao_id, $this->_escola_id);
        $aluno = array();
        $aluno_list = array();

        if ($aluno_nota->id <= 0) {
            $alunos = $this->_alunoRepository->getListByTurma($turma_id);

            foreach ($alunos as $aluno_obj):

                $aluno['id'] = $aluno_obj->id;
                $aluno['nome'] = $aluno_obj->nome;
                $aluno['nota'] = AppUtil::convertFloatToString(0);
                $aluno_list[] = $aluno;

            endforeach;
        } else {

            foreach ($aluno_nota->AlunoNotaItem as $aluno_obj):

                if ($aluno_obj->Aluno->status == Aluno::ATIVO) {
                    $aluno['id'] = $aluno_obj->Aluno->id;
                    $aluno['nome'] = $aluno_obj->Aluno->nome;
                    $aluno['nota'] = AppUtil::convertFloatToString($aluno_obj->nota);
                    $aluno_list[] = $aluno;
                }
            endforeach;
        }

        $this->_helper->json(Zend_Json::encode($aluno_list));


        ///$alunos = $this->_alunoRepository->getListByAlunoNota($turma_id,$disciplina_id, $avaliacao_id);
        //$alunos_nota = $this->_alunoNotaItemRepository->getNotas($disciplina_id, $avaliacao_id);
    }

}
