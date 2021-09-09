<?php

class Ajax_ProdutoController extends Zend_Controller_Action {

    private $_produtoCursoRepository;
    private $_produtoAlunoRepository;
    private $_escola;

    public function init() {
        $this->_produtoCursoRepository = new ProdutoCursoRepository();
        $this->_produtoAlunoRepository = new ProdutoAlunoRepository();
        $this->_escola = SessionUtil::getEscolaSession();
        $this->_helper->layout->disableLayout();
    }

    public function listAction() {

        $curso_id = $this->getRequest()->getParam('id');
        $aluno_id = $this->getRequest()->getParam('aluno_id');

        $produto = array();
        $produto_list = array();
       

        if ($aluno_id == 0) {
            $produto_curso = $this->_produtoCursoRepository->getListByCurso($curso_id, $this->_escola);

            foreach ($produto_curso as $produto_curso_obj):
                $produto['id'] = $produto_curso_obj->produto_id;
                $produto['checked'] = '';
                $produto['class'] = 'habilitar';
                $produto['descricao'] = $produto_curso_obj->Produto->descricao;
                $produto['valor'] = AppUtil::convertFloatToString($produto_curso_obj->valor);
                $produto['parcela_selecionada'] = 1;
                $produto['observacao'] = "";

                $produto['parcelas'] = $produto_curso_obj->parcelas;
                $produto_list[] = $produto;

            endforeach;
        }else {
            $produto_aluno = $this->_produtoAlunoRepository->getListByAluno($aluno_id, $this->_escola);

            $produto_id = array();
            foreach ($produto_aluno as $produto_aluno_obj):
                $produto_id[] = $produto_aluno_obj->ProdutoCurso->produto_id;
                $produto['id'] = $produto_aluno_obj->ProdutoCurso->produto_id;
                $produto['checked'] = 'checked';
                $produto['class'] = 'desabilitar';
                $produto['descricao'] = $produto_aluno_obj->descricao;
                $produto['valor'] = AppUtil::convertFloatToString($produto_aluno_obj->valor);
                $produto['parcelas'] = $produto_aluno_obj->parcelas;
                $produto['observacao'] = $produto_aluno_obj->observacao;
                $produto['parcela_selecionada'] = $produto_aluno_obj->parcelas;
                $produto['status'] = $produto_aluno_obj->getStatusLabel();
                $produto_list[] = $produto;
            endforeach;

            $produto_curso = $this->_produtoCursoRepository->getListByCurso($curso_id, $this->_escola, $produto_id);
            foreach ($produto_curso as $produto_curso_obj):
                $produto['id'] = $produto_curso_obj->produto_id;
                $produto['checked'] = '';
                $produto['class'] = 'habilitar';
                $produto['descricao'] = $produto_curso_obj->Produto->descricao;
                $produto['observacao'] = '';
                $produto['parcela_selecionada'] = 1;
                $produto['status'] = '';
                $produto['valor'] = AppUtil::convertFloatToString($produto_curso_obj->valor);
                $produto['parcelas'] = $produto_curso_obj->parcelas;
                $produto_list[] = $produto;
            endforeach;
        }

        // var_dump($produto_list);
        //die;

        $this->_helper->json(Zend_Json::encode($produto_list));
    }

}
