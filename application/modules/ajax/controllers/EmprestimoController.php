<?php

class Ajax_EmprestimoController extends Zend_Controller_Action {

    private $_emprestimoRepository;
    private $_escolaRepository;
    private $_escola;

    public function init() {
        $this->_helper->layout->disableLayout();
        $this->_emprestimoRepository = new EmprestimoRepository();
        $this->_escolaRepository = new EscolaRepository();
        $this->_escola = SessionUtil::getEscolaSession();
    }

    public function listAction() {

        $codigo_inteiro = $this->getRequest()->getParam('codigo');

        $escola = $this->_escolaRepository->getById($this->_escola);
        $tamanho_codigo = strlen($escola->EscolaConfiguracao[0]->referencia_biblioteca);

        $codigo_livro = substr($codigo_inteiro, 0, $tamanho_codigo);
        $unidade = substr($codigo_inteiro, $tamanho_codigo + 1);

        $emprestimo = $this->_emprestimoRepository->getEmprestimo($this->_escola, $codigo_livro, $unidade);

        $livro_json = array();
        $livro_json['id'] = $emprestimo->id;
        $livro_json['titulo'] = $emprestimo->Livro->titulo;
        $livro_json['data_emprestimo'] = AppUtil::convertDateToString($emprestimo->data_emprestimo);
        $livro_json['data_devolucao_prevista'] = AppUtil::convertDateToString($emprestimo->data_devolucao_prevista);
        $livro_json['status'] = $emprestimo->status;

        if ($emprestimo->status == Emprestimo::ATRASADO) {
            $diferenca_dias = AppUtil::getDiferecaDias($emprestimo->data_devolucao_prevista, date('Y-m-d'));
            $valor_multa = ($diferenca_dias * $emprestimo->Livro->LivroGrauRelevancia->valor);
            $livro_json['multa'] = AppUtil::convertFloatToString($valor_multa);
        }

        $this->_helper->json(Zend_Json::encode($livro_json));
    }

}
