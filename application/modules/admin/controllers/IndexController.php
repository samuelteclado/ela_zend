<?php

class Admin_IndexController extends Zend_Controller_Action {

    private $_usuarioRepository;

    public function init() {
        $this->_usuarioRepository = new UsuarioRepository();
    }

    public function loginAction() {
        $auth = Zend_Auth::getInstance();
        switch ($auth->getIdentity()->usuario_grupo_id) {
            case UsuarioGrupo::ADMINISTRADOR:
                return $this->_helper->redirector->setGotoSimple('index', 'principal', 'admin');
            case UsuarioGrupo::FUNCIONARIO:
                return $this->_helper->redirector->goToRoute(array('controller' => 'funcionario'), null, true);
            case UsuarioGrupo::SECRETARIA:
                return $this->_helper->redirector->goToRoute(array('controller' => 'secretaria'), null, true);
        }
    }
    public function indexAction() {

        $auth = Zend_Auth::getInstance();
        switch ($auth->getIdentity()->usuario_grupo_id) {
            case UsuarioGrupo::ADMINISTRADOR:
                return $this->_helper->redirector->setGotoSimple('index', 'principal', 'admin');
            case UsuarioGrupo::FUNCIONARIO:
                return $this->_helper->redirector->goToRoute(array('controller' => 'funcionario'), null, true);
            case UsuarioGrupo::SECRETARIA:
                return $this->_helper->redirector->goToRoute(array('controller' => 'secretaria'), null, true);
        }
    }

    public function lembrarSenhaAction() {
        $this->_helper->layout->disableLayout();

        if ($this->getRequest()->isPost()) {
            $email = $this->getRequest()->getParam('email');
            $usuario = $this->_usuarioRepository->getUsuario($email);
            if ($usuario == false) {
                $this->_helper->FlashMessenger(array('error' => 'Desculpe, mas o email digitado não existe em nosso sistema.'));
            } else {
                $this->_sendMail($usuario);
                $this->_redirect($this->view->baseURL . '/admin');
            }
        }
    }

}
