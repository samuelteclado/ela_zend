<?php

class Ajax_AlunoController extends Zend_Controller_Action {

    private $_alunoRepository;
    private $_alunoPresencaItemRepository;
    private $_escola;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_alunoRepository = new AlunoRepository();
        $this->_alunoPresencaItemRepository = new AlunoPresencaItemRepository();
        $this->_escola = SessionUtil::getEscolaSession();
    }

    public function listAction() {

        $turma_id = $this->getRequest()->getParam('id');
        $presenca_id = $this->getRequest()->getParam('presenca');

        $alunos = $this->_alunoRepository->getListByTurma($turma_id);
        $alunos_presenca = $this->_alunoPresencaItemRepository->getAlunosByPresenca($presenca_id);

        $aluno = array();
        $aluno_list = array();

        foreach ($alunos_presenca as $item) {
            $alunos_array[] = $item->aluno_id;
        }

        foreach ($alunos as $aluno_obj):

            $aluno['id'] = $aluno_obj->id;
            $aluno['nome'] = $aluno_obj->nome;
            $aluno['checked'] = ViewUtil::fillFieldCheckMultiple($alunos_array, $aluno_obj->id);

            $aluno_list[] = $aluno;

        endforeach;
        $this->_helper->json(Zend_Json::encode($aluno_list));
    }

    public function listByMatriculaAction() {

        $matricula = $this->getRequest()->getParam('matricula');

        $aluno = $this->_alunoRepository->getListByMatricula($this->_escola, $matricula);

        $aluno_json = array();
        $aluno_json['id'] = $aluno->id;
        $aluno_json['nome'] = $aluno->nome;
        $aluno_json['email'] = $aluno->email;
        $aluno_json['curso'] = $aluno->Turma->Curso->descricao;
        $aluno_json['turma'] = $aluno->Turma->descricao;

        $this->_helper->json(Zend_Json::encode($aluno_json));
    }

}
