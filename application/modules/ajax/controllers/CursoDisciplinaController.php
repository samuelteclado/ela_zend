<?php

class Ajax_CursoDisciplinaController extends Zend_Controller_Action {

    private $_cursoDisciplinaRepository;
    private $_disciplinaRepository;
    private $_cursoRepository;
    private $_professorTurma;

    public function init() {
        $this->_cursoDisciplinaRepository = new CursoDisciplinaRepository();
        $this->_disciplinaRepository = new DisciplinaRepository();
        $this->_cursoRepository = new CursoRepository();
        $this->_professorTurma = new ProfessorTurmaRepository();
        $this->_helper->layout->setLayout('administrador_no_menu');
    }

    public function listAction() {

        $curso_id = $this->getRequest()->getParam('curso');
        $turma_id = $this->getRequest()->getParam('turma');



        $disciplinas = $this->_disciplinaRepository->getByCurso($curso_id);

        $professor_turma = $this->_professorTurma->getByTurma($turma_id);

        $disciplina_obj = array();
        $disciplina_list = array();

        foreach ($disciplinas as $disciplina):

            $disciplina_obj['id'] = $disciplina->id;
            $disciplina_obj['descricao'] = $disciplina->descricao;
            
            $disciplina_obj['professor_selecionado'] = $this->_getIdProfessor($professor_turma, $disciplina->id);;
            $disciplina_obj['professores'] = $disciplina->getProfessoresArray();

            
            $disciplina_list[] = $disciplina_obj;

        endforeach;

        $this->_helper->json(Zend_Json::encode($disciplina_list));
    }

    private function _getIdProfessor($professor_turma, $disciplina_id) {

        foreach ($professor_turma as $professor) :
            if ($professor->disciplina_id == $disciplina_id) {
                return $professor->professor_id;
            }

        endforeach;
        
        return 0;
    }

}
