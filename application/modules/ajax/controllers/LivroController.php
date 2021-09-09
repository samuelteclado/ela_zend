<?php

class Ajax_LivroController extends Zend_Controller_Action {

    private $_livroRepository;
    private $_escolaRepository;
    private $_escola;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_livroRepository = new LivroRepository();
        $this->_escolaRepository = new EscolaRepository();
        $this->_escola = SessionUtil::getEscolaSession();
    }

    public function listAction() {

        $codigo_inteiro = $this->getRequest()->getParam('codigo');

        $escola = $this->_escolaRepository->getById($this->_escola);
        $tamanho_codigo = strlen($escola->EscolaConfiguracao[0]->referencia_biblioteca);

        $codigo_livro = substr($codigo_inteiro, 0, $tamanho_codigo);
        $unidade = substr($codigo_inteiro, $tamanho_codigo + 1);

        $livro = $this->_livroRepository->getByCodigo($this->_escola, $codigo_livro);


        $livro_json = array();
        $livro_json['id'] = $livro->id;
        $livro_json['titulo'] = $livro->titulo;
        $livro_json['autoria'] = $livro->autoria;
        $livro_json['editora'] = $livro->editora;
        $livro_json['registro'] = $livro->registro;
        $livro_json['localizacao'] = $livro->localizacao;
        $livro_json['tiragem'] = $livro->tiragem;
        $livro_json['ano_publicacao'] = $livro->ano_publicacao;
        $livro_json['unidade_livro'] = $unidade;
        $livro_json['data_devolucao_prevista'] = AppUtil::setAddDay($livro->LivroGrauRelevancia->limite_dias);
        
        $this->_helper->json(Zend_Json::encode($livro_json));
    }

}
