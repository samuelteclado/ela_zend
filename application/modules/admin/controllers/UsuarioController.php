<?php

class Admin_UsuarioController extends Zend_Controller_Action {

    private $_usuarioRepository;
    private $_usuarioGrupoRepository;
    private $_empresa_id;

    public function init() {
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_usuarioGrupoRepository = new UsuarioGrupoRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status != ?', Usuario::EXCLUIDO);
        $filter->addFilter('email != ?', 'admin');
        $filter->addTextFilter('nome', $params['nome']);
        $filter->addTextFilter('email', $params['email']);
        $filter->addSelectFilter('status', $params['status']);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_usuarioRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(20);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {
        $form = new UploadForm();

        if ($this->getRequest()->isPost()) {
            $usuario = new Usuario();

            $data = $this->getRequest()->getPost();

            $this->_setData($usuario, $data);

            $validate = $this->_validate($usuario);
            if (!isset($validate) || $validate == "") {

                $usuario->save();
                $this->_saveImage($usuario, $form, $data);


                $this->_helper->FlashMessenger('Usuário cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/usuario/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->form = $form;
                $this->view->usuario = $usuario;
                $this->view->grupos = $this->_usuarioGrupoRepository->getList();
            }
        } else {
            $this->view->form = $form;
            $this->view->grupos = $this->_usuarioGrupoRepository->getList();
        }
    }

    public function editarAction() {
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
            $this->view->grupos = $this->_usuarioGrupoRepository->getList();
        } else {
            $id = $this->getRequest()->getParam('id');
            $usuario = $this->_usuarioRepository->getById($id);

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

                    $this->_helper->FlashMessenger('Usuário alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/usuario/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->usuario = $usuario;
                    $this->view->grupos = $this->_usuarioGrupoRepository->getList();
                }
            } else {
                $this->view->form = $form;
                $this->view->usuario = $usuario;
                $this->view->grupos = $this->_usuarioGrupoRepository->getList();
            }
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $usuario = $this->_usuarioRepository->getById($id, $this->_empresa_id);
        if ($usuario != FALSE) {
            $usuario->status = Usuario::EXCLUIDO;
            $usuario->save();

            $this->_helper->FlashMessenger('Usuário removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um usuário que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/usuario/');
    }

    private function _setData(Usuario $usuario, $data) {
        $caracter_remove = array("(", ")", "-", "/", " ", ".");
        $usuario->nome = AppUtil::setFirstUpWord($data['nome']);
        $usuario->sobrenome = AppUtil::setFirstUpWord($data['sobrenome']);
        $usuario->email = strtolower($data['email']);
        $usuario->usuario_grupo_id = $data['usuario_grupo_id'];
        $usuario->status = $data['status'];
        $usuario->empresa_id = $this->_empresa_id;
        $usuario->cpf = str_replace($caracter_remove, "", $data['cpf']);
        $usuario->admissao = AppUtil::convertStringToDate($data['admissao']);
        $usuario->demissao = AppUtil::convertStringToDate($data['demissao']);
        $usuario->data_aniversario = AppUtil::convertStringToDate($data['data_aniversario']);
        $usuario->cor = $data['cor'];
        if ($usuario->id <= 0) {
            $usuario->senha = ($data['senha']);
            if ($usuario->senha == "")
                $usuario->senha = (AppUtil::gerar_senha(8, true, true, true, true));
        }

        if ($usuario->Endereco[0]->id <= 0) {
            $usuario_endereco = new Endereco();
            $this->_setDataEndereco($usuario_endereco, $data);
            $usuario->Endereco[0] = $usuario_endereco;
        } else {
            $usuario_endereco = $usuario->Endereco[0];
            $this->_setDataEndereco($usuario_endereco, $data);
        }
    }

    private function _setDataEndereco(Endereco $endereco, $data) {
        $caracter_remove = array("(", ")", "-", " ", ".");
        $endereco->cep = str_replace($caracter_remove, "", $data['cep']);
        $endereco->bairro = AppUtil::setFirstUpWord($data['bairro']);
        $endereco->numero = (int) $data['numero'];
        $endereco->cidade = AppUtil::setFirstUpWord($data['cidade']);
        $endereco->estado = AppUtil::setWordUpper($data['estado']);
        $endereco->logradouro = AppUtil::setFirstUpWord($data['logradouro']);
        $endereco->complemento_endereco = AppUtil::setFirstUpWord($data['complemento_endereco']);
        $endereco->telefone_1 = str_replace($caracter_remove, "", $data['telefone_1']);
        $endereco->telefone_2 = str_replace($caracter_remove, "", $data['telefone_2']);

    }

    private function _setDataSenha(Usuario $usuario, $data) {
        if (($usuario->senha == $data['senha_atual']) && ($data['nova_senha'] == $data['redigite_senha']))
            $usuario->senha = $data['nova_senha'];
    }

    public function reenviarAction() {
        $id = $this->getRequest()->getParam('id');

        $usuario = $this->_usuarioRepository->getById($id);

        $this->_sendMail($usuario);

        $this->_helper->FlashMessenger('Email de boas vindas reenviado com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/usuario/');
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
        } else if ($this->_usuarioRepository->isExiste($usuario, $this->_empresa_id)) {
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

    private function _sendMail(Usuario $usuario) {

        $app_email = Zend_Registry::getInstance()->get('email');
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('usuario', $usuario);
        $html->assign('empresa', $usuario->Empresa);
        $html->assign('remetente', $app_email->mail->de);


        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = $usuario->email;

        $subject = "Seja Bem Vindo(a) ao Espaço ELA";
        $body = $html->render('lembrar_senha.phtml');

        EmailUtil::send($from, $to, $subject, $body);
    }

}
