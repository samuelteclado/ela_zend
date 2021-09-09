<?php

class Ajax_DisciplinaController extends Zend_Controller_Action {

    private $_disciplinaRepository;
    private $_turmaRepository;
    private $_usuarioRepository;

    public function init() {
        $this->_disciplinaRepository = new DisciplinaRepository();
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_turmaRepository = new TurmaRepository();
        $this->_helper->layout->disableLayout();
    }
    
     public function listAction() {

        $turma_id = $this->getRequest()->getParam('id');
        $turma = $this->_turmaRepository->getById($turma_id);
        $usuario_id = SessionUtil::getIdUser();
        $usuario = $this->_usuarioRepository->getById($usuario_id);
        $professor = $usuario->Professor[0]->id;

        if(isset($professor)){
            $disciplinas = $this->_disciplinaRepository->getListByTurma($turma_id, $professor);
        }else{
            $disciplinas = $this->_disciplinaRepository->getByCurso($turma->Curso->id);
        }

        $disciplina = array();
        $disciplina_list = array();

        foreach ($disciplinas as $disciplina_obj):

            $disciplina['id'] = $disciplina_obj->id;
            $disciplina['descricao'] = $disciplina_obj->descricao;

            $disciplina_list[] = $disciplina;

        endforeach;

        $this->_helper->json(Zend_Json::encode($disciplina_list));
    }

}
