<?php

class Ajax_AlunoController extends Zend_Controller_Action {

    private $_clienteRepository;
    private $_alunoPresencaItemRepository;
    private $_escola;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_clienteRepository = new ClienteRepository();
        $this->_alunoPresencaItemRepository = new AlunoPresencaItemRepository();
        $this->_escola = SessionUtil::getEscolaSession();
    }

    public function listAction() {

        $turma_id = $this->getRequest()->getParam('id');
        $presenca_id = $this->getRequest()->getParam('presenca');

        $clientes = $this->_clienteRepository->getListByTurma($turma_id);
        $alunos_presenca = $this->_alunoPresencaItemRepository->getAlunosByPresenca($presenca_id);

        $aluno = array();
        $aluno_list = array();

        foreach ($alunos_presenca as $item) {
            $alunos_array[] = $item->aluno_id;
        }

        foreach ($clientes as $cliente_obj):

            $aluno['id'] = $cliente_obj->id;
            $aluno['nome'] = $cliente_obj->nome;
            $aluno['checked'] = ViewUtil::fillFieldCheckMultiple($alunos_array, $cliente_obj->id);

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
