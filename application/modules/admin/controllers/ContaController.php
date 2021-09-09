<?php

class Admin_ContaController extends Zend_Controller_Action {

    private $_usuarioRepository;
    private $_clienteRepository;
    private $_erro;

    public function init() {
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_clienteRepository = new ClienteRepository();
        $this->_erro = 0;
    }

    public function lembrarsenhaAction() {

        if ($this->getRequest()->isPost()) {
            $email = $this->getRequest()->getParam('email');
            $usuario = $this->_usuarioRepository->getUsuario($email);
            if ($usuario == false) {
                $this->_helper->FlashMessenger(array('error' => 'Desculpe, mas o email digitado não existe em nosso sistema.'));
            } else {
                $this->_sendMail($usuario);
                $this->_helper->FlashMessenger(array('sucess' => 'Em breve enviaremos um email para você.'));
                $this->_redirect($this->view->baseURL . '/admin');
            }
        }
    }


    private function _sendMailUsuario($usuario) {

        $app_email = Zend_Registry::getInstance()->get('email');

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('usuario', $usuario);
        $html->assign('empresa', $usuario->Empresa);
        $html->assign('remetente', $app_email->mail->de);

        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to[] = $usuario->email;

        $subject = "[Lembrar Senha] Espaço ELA ";
        $body = $html->render('lembrar_senha.phtml');

        EmailUtil::send($from, $to, $subject, $body);
    }

    private function _sendMail($usuario) {
 
        $app_email = Zend_Registry::getInstance()->get('email');

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('usuario', $usuario);
        $html->assign('empresa', $usuario->Empresa);
        $html->assign('remetente', $app_email->mail->de);
   
        
        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = $usuario->email;

        $subject = "[Lembrar Senha] Espaço ELA ";
        $body = $html->render('lembrar_senha.phtml');
 
        EmailUtil::send($from, $to, $subject, $body);

    }

}
