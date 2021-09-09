<?php

class Site_InstitucionalController extends Zend_Controller_Action {

    private $destinatario = 'verticalvert@verticalvert.com.br';

    public function init() {
        $this->_helper->layout->setLayout('site');
    }

    public function agendaAction() {
        $agendaRepository = new SiteAgendaRepository();
        $this->_helper->layout->setLayout('site_sem_menu');
        $this->view->agenda = $agendaRepository->getPublished();
    }

    public function fotosAction() {
        $fotosRepository = new SiteFotosRepository();
        $this->_helper->layout->setLayout('site_sem_menu');
        $this->view->fotos = $fotosRepository->getPublished();
     }

    public function indexAction() {
        
    }

    public function somosAction() {
        
    }

    public function servicoAction() {
        
    }

    public function clienteAction() {
        
    }

    public function parceiroAction() {//Corrigir
        $parceiroRepository = new SiteParceiroRepository();

        $this->view->categorias = $parceiroRepository->getParceiroCategoriaList();
    }

    public function contatoAction() {
        if ($this->getRequest()->isPost()) {
            $form_nome = $this->_request->getParam('nome');
            $form_email = $this->_request->getParam('email');
            $form_telefone = $this->_request->getParam('telefone');
            $form_assunto = $this->_request->getParam('assunto');
            switch ($form_assunto) {
                case 0:
                    $form_assunto = 'Não Informado';
                    break;
                case 1:
                    $form_assunto = 'Logística de Eventos';
                    break;
                case 2:
                    $form_assunto = 'Financeiro';
                    break;
                case 3:
                    $form_assunto = 'Sugestões';
                    break;
                case 4:
                    $form_assunto = 'Robson Terra';
                    break;
                case 5:
                    $form_assunto = 'Vanesca Toledo';
                    break;
            }
            $assunto_text = '[VERTICAL VERT] Contato';

            $form_mensagem = $this->_request->getParam('mensagem');

            $mensagem_text = '<b>Nome: </b>' . $form_nome . '<br />' .
                    '<b>Email: </b>' . $form_email . '<br />' .
                    '<b>Telefone: </b>' . $form_telefone . '<br />' .
                    '<b>Assunto: </b>' . $form_assunto . '<br />' .
                    '<p><b>Mensagem: </b>' .
                    $form_mensagem .
                    '</p>';

            EmailUtil::sendMail(utf8_decode($form_nome), $this->destinatario, utf8_decode($assunto_text), utf8_decode($mensagem_text));
        }
    }

    public function descomplicandoAction() {
        if ($this->getRequest()->isPost()) {
            $form_nome = $this->_request->getParam('nome');
            $form_email = $this->_request->getParam('email');
            $form_assunto = '[VERTICAL VERT] Descomplicando';

            $form_telefone = $this->_request->getParam('telefone');
            $form_faculdade = $this->_request->getParam('faculdade');
            $form_curso = $this->_request->getParam('curso');
            $form_turno = $this->_request->getParam('turno');
            switch ($form_turno) {
                case 0:
                    $form_turno = 'Não Informado';
                    break;
                case 1:
                    $form_turno = 'Manhã';
                    break;
                case 2:
                    $form_turno = 'Tarde';
                    break;
                case 3:
                    $form_turno = 'Noite';
                    break;
            }

            $form_previsao_formatura = $this->_request->getParam('previsao_formatura');

            $missa = $this->_request->getParam('missa');
            $baile = $this->_request->getParam('baile');
            $colacao = $this->_request->getParam('colacao');

            $eventos_aluno = '<ul>';
            if (isset($missa)) {
                $eventos_aluno = $eventos_aluno . '<li> Missa: ' . $this->_request->getParam('missa_aluno') . ' Alunos </li>';
            }

            if (isset($baile)) {
                $eventos_aluno = $eventos_aluno . '<li> Baile: ' . $this->_request->getParam('baile_aluno') . ' Alunos </li>';
            }

            if (isset($colacao)) {
                $eventos_aluno = $eventos_aluno . '<li> Colação: ' . $this->_request->getParam('colacao_aluno') . ' Alunos </li>';
            }
            $eventos_aluno = $eventos_aluno . '</ul>';

            $form_mensagem = $this->_request->getParam('mensagem');

            $duvidas = $this->_request->getParam('duvidas');
            $duvidas_text = '<ul>';
            foreach ($duvidas as $duvida) {
                $duvidas_text = $duvidas_text . '<li>' . $duvida . '</li>';
            }
            $duvidas_text = $duvidas_text . '</ul>';

            $mensagem_text = '<b>Nome: </b>' . $form_nome . '<br />' .
                    '<b>Email: </b>' . $form_email . '<br />' .
                    '<p><b>Dúvidas: </b>' . $duvidas_text . '</p><br />' .
                    '<b>Telefone: </b>' . $form_telefone . '<br />' .
                    '<b>Faculdade: </b>' . $form_faculdade . '<br />' .
                    '<b>Curso: </b>' . $form_curso . '<br />' .
                    '<b>Turno: </b>' . $form_turno . '<br />' .
                    '<b>Previsão de Formatura: </b>' . $form_previsao_formatura . '<br />' .
                    '<b>Evento / Aluno: </b>' . $eventos_aluno .
                    '<b>Mensagem: </b>' . $form_mensagem;

            EmailUtil::sendMail(utf8_decode($form_nome), $this->destinatario, utf8_decode($form_assunto), utf8_decode($mensagem_text));
        }
    }

    public function orcamentoAction() {
        if ($this->getRequest()->isPost()) {
            $form_nome = $this->_request->getParam('nome');
            $form_email = $this->_request->getParam('email');
            $form_telefone = $this->_request->getParam('telefone');
            $form_faculdade = $this->_request->getParam('faculdade');
            $form_curso = $this->_request->getParam('curso');
            $form_turno = $this->_request->getParam('turno');
            switch ($form_turno) {
                case 0:
                    $form_turno = 'Não Informado';
                    break;
                case 1:
                    $form_turno = 'Manhã';
                    break;
                case 2:
                    $form_turno = 'Tarde';
                    break;
                case 3:
                    $form_turno = 'Noite';
                    break;
            }

            $form_previsao_formatura = $this->_request->getParam('previsao_formatura');

            $missa = $this->_request->getParam('missa');
            $baile = $this->_request->getParam('baile');
            $colacao = $this->_request->getParam('colacao');

            $eventos_aluno = '<ul>';
            if (isset($missa)) {
                $eventos_aluno = $eventos_aluno . '<li> Missa: ' . $this->_request->getParam('missa_aluno') . ' Alunos </li>';
            }

            if (isset($baile)) {
                $eventos_aluno = $eventos_aluno . '<li> Baile: ' . $this->_request->getParam('baile_aluno') . ' Alunos </li>';
            }

            if (isset($colacao)) {
                $eventos_aluno = $eventos_aluno . '<li> Colação: ' . $this->_request->getParam('colacao_aluno') . ' Alunos </li>';
            }
            $eventos_aluno = $eventos_aluno . '</ul>';

            $form_assunto = '[VERTICAL VERT] Orçamento';
            $form_mensagem = $this->_request->getParam('mensagem');

            $mensagem_text = '<b>Nome: </b>' . $form_nome . '<br />' .
                    '<b>Email: </b>' . $form_email . '<br />' .
                    '<b>Telefone: </b>' . $form_telefone . '<br />' .
                    '<b>Faculdade: </b>' . $form_faculdade . '<br />' .
                    '<b>Curso: </b>' . $form_curso . '<br />' .
                    '<b>Turno: </b>' . $form_turno . '<br />' .
                    '<b>Previsão de Formatura: </b>' . $form_previsao_formatura . '<br />' .
                    '<b>Evento / Aluno: </b>' . $eventos_aluno .
                    '<b>Mensagem: </b>' . $form_mensagem;

            EmailUtil::sendMail(utf8_decode($form_nome), $this->destinatario, utf8_decode($form_assunto), utf8_decode($mensagem_text));
        }
    }

}

