<?php

class Funcionario_PerfilController extends Zend_Controller_Action {

    private $_usuarioRepository;
    private $_empresaRepository;
    private $_empresa;

    public function init() {
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_empresaRepository = new EscolaRepository();
        $this->_empresa = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $id = SessionUtil::getIdUser();
        $usuario = $this->_usuarioRepository->getById($id);
        
        $remover_imagem = $this->getRequest()->getParam('remove');

        $form = new UploadForm();

        if ($remover_imagem > 0) {
            $usuario = $this->_usuarioRepository->getById($remover_imagem);

            $data = $this->getRequest()->getPost();
            $this->_setData($usuario, $data);

            $this->_deleteImage($usuario);

            $this->_helper->FlashMessenger(array('info' => 'Imagem do perfil removida com sucesso.'));
            $this->view->form = $form;
            $this->view->usuario = $usuario;
        } else {


            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($usuario, $data);
                if ($data['senha_atual'] != "") {
                    $validate = $this->_validateSenha($usuario, $data);
                    if (!isset($validate) || $validate == "")
                        $this->_setDataSenha($usuario, $data);
                }
                $validate .= $this->_validate($usuario);
                if (!isset($validate) || $validate == "") {
                    $usuario->save();
                    $this->_saveImage($usuario, $form, $data);

                    $this->_helper->FlashMessenger('Perfil alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/funcionario/perfil/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->usuario = $usuario;
                }
            } else {
                $this->view->form = $form;
                $this->view->usuario = $usuario;
            }
        }
    }


    private function _setData(Usuario $usuario, $data) {
        $usuario->nome = AppUtil::setFirstUpWord($data['nome']);
        $usuario->email = strtolower($data['email']);     
    }

    private function _setDataSenha(Usuario $usuario, $data) {
        if (($usuario->senha == $data['senha_atual']) && ($data['nova_senha'] == $data['redigite_senha']))
            $usuario->senha = $data['nova_senha'];
    }

    private function _validateSenha(Usuario $usuario, $data) {

        if ($usuario->senha != $data['senha_atual']) {
            $result .= "<li>A <b>Senha Atual</b> está incorreta.</li>";
        }

        if ($data['nova_senha'] == "") {
            $result .= "<li>Digite uma nova senha.</li>";
        }
        if ($data['nova_senha'] != $data['redigite_senha']) {
            $result .= "<li>As <b>Senhas</b> não conferem.</li>";
        }

        return $result;
    }

    private function _validate(Usuario $usuario) {
        if ($usuario->nome == "")
            $result .= "<li>O campo <b>Nome </b> deve ser informado.</li>";

        $result .= $this->_validateEmail($usuario);

        if ($usuario->senha == "")
            $result .= "<li>O campo <b>Senha</b> deve ser informado.</li>";

        return $result;
    }

     private function _validateEmail(Usuario $usuario) {
        if ($usuario->email == "") {
            $result .= "<li>O campo <b>Email</b> deve ser informado.</li>";
        } else if ($this->_usuarioRepository->isExiste($usuario, $this->_empresa)) {
            $result .= "<li>O <b>Email</b> informado já esta registrado.</li>";
        }

        return $result;
    }

    private function _getFileName(Usuario $usuario) {
        chdir(APPLICATION_UPLOAD_PATH . '/u');
        return getcwd() . '/' . AppUtil::getFileName($usuario);
    }

    private function _saveImage(Usuario $usuario, $form, $data) {
        if ($form->isValid($data)) {
            $form->getValues();

            $filename = $form->file->getFileName(null, false);
            $filename = getcwd() . '/' . $filename;

            $filename_new = $this->_getFileName($usuario);

            copy($filename, $filename_new);
            unlink($filename);
        }
    }

    private function _deleteImage(Usuario $usuario) {
        $filename = $this->_getFileName($usuario);
        unlink($filename);
    }

}
