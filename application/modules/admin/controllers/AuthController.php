<?php

class Admin_AuthController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function loginAction() {
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();

            $email = $data['usuario'];
            $senha = $data['senha'];

            if (!isset($email) || $email == '' || !isset($senha) || $senha == '') {
                $this->_helper->FlashMessenger(array('error' => 'Os campos para autenticação devem ser preenchidos.'));
                $this->_redirect($this->view->baseUrl() . '/admin/');
            }

            $login = $this->_login($email, $senha);

            if ($login === true) {
                $this->_redirect($this->view->baseUrl() . '/admin/principal/');
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => $login));
            $this->_redirect($this->view->baseUrl() . '/admin/');
        }
    }

    private function _login($login, $senha) {
        $db_config = Zend_Registry::getInstance()->get('database');
        $dbAdapter = Zend_Db::factory($db_config->db->adapter, array(
                    'host' => $db_config->db->params->host,
                    'username' => $db_config->db->params->username,
                    'password' => $db_config->db->params->password,
                    'dbname' => $db_config->db->params->dbname
        ));

        $adpter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $adpter->setTableName('usuario')
                ->setIdentityColumn('email')
                ->setCredentialColumn('senha')
                ->setCredentialTreatment('? AND status = "' . Usuario::ATIVO . '"');

        $adpter->setIdentity($login)
                ->setCredential($senha);

        $auth = Zend_Auth::getInstance();
        $resultado = $auth->authenticate($adpter);

        if ($resultado->isValid()) {
            $info = $adpter->getResultRowObject(null, 'senha');
            $storage = $auth->getStorage();
            $storage->write($info);

           
            switch ($info->usuario_grupo_id) {
                case UsuarioGrupo::ADMINISTRADOR:
                    return $this->_helper->redirector->setGotoSimple('index', 'principal', 'admin');
                case UsuarioGrupo::FUNCIONARIO:
                    return $this->_helper->redirector->goToRoute(array('controller' => 'funcionario'), null, true);
            }
        } else {
            $this->_helper->FlashMessenger(array('error' => 'Email e/ou senha inválidos!'));
            $this->_redirect($this->view->baseUrl() . '/admin');
        }


        return $this->_redirect($this->view->baseUrl() . '/institucional/login');
    }

    public function logoutAction() {

        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_helper->FlashMessenger('Logout efetuado sucesso.');
        return $this->_redirect($this->view->baseUrl() . '/admin');
    }

}
