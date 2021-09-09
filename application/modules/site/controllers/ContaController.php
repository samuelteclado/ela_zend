<?php

class Site_ContaController extends Zend_Controller_Action {

    private $_usuarioRepository;
    private $_erro;

    public function init() {
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_erro = 0;
    }

    public function lembrarsenhausuarioAction() {
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $email = $this->getRequest()->getParam('email');
            $usuario = $this->_usuarioRepository->getUsuario($email);
            if ($usuario == false) {
                $this->_helper->FlashMessenger(array('error' => 'Desculpe, mas o email digitado não existe em nosso sistema.'));
            } else {
                $this->_sendMail($usuario);
                $this->_redirect($this->view->baseURL . '/institucional');
            }
        }
    }
    
    public function lembraralunoAction() {
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $email = $this->getRequest()->getParam('email');
            $usuario = $this->_usuarioRepository->getUsuario($email);
            if ($usuario == false) {
                $this->_helper->FlashMessenger(array('error' => 'Desculpe, mas o email digitado não existe em nosso sistema.'));
            } else {
                $this->_sendMail($usuario);
                $this->_redirect($this->view->baseURL . '/aluno');
            }
        }
    }
  

    private function _sendMail($usuario) {
        
       $app_email = Zend_Registry::getInstance()->get('email');

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('usuario', $usuario);
        $html->assign('remetente', $app_email->mail->de);

        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = $usuario->email;

        $subject = "[Lembrar Senha] Siges ";
        $body = $html->render('lembrar_senha.phtml');

        EmailUtil::send($from, $to, $subject, $body);
    }

}

