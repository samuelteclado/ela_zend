<?php

class Funcionario_ClienteController extends Zend_Controller_Action {

    private $_clienteRepository;
    private $_procedimentoRepository;
    private $_empresa_id;

    public function init() {
        $this->_clienteRepository = new ClienteRepository();
        $this->_procedimentoRepository = new ProcedimentoRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
        $params = $this->getRequest()->getParams();
        $this->_layout = $params['nolayout'];
        $this->_helper->layout->setLayout(ViewUtil::disableLayout($this->_layout));
        $this->view->layout = $this->_layout;

    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);

        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addFilter('status != ?', Cliente::EXCLUIDO);
        $filter->addFilter('email != ?', 'admin');
        $filter->addTextFilter('nome', $params['nome']);
        $filter->addTextFilter('sobrenome', $params['sobrenome']);
        $filter->addTextFilter('email', $params['email']);
        $filter->addSelectFilter('status', $params['status']);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_clienteRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(40);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);


        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {
        $form = new UploadForm();

        if ($this->getRequest()->isPost()) {
            $cliente = new Cliente();

            $data = $this->getRequest()->getPost();

            $this->_setData($cliente, $data);

            $validate = $this->_validate($cliente, $data);
            if (!isset($validate) || $validate == "") {

                $cliente->save();
                $this->_saveImage($cliente, $form, $data);


                $this->_helper->FlashMessenger('Cliente cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/funcionario/cliente/?nolayout=' . $this->_layout);
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->form = $form;
                $this->view->cliente = $cliente;
            }
        } else {
            $this->view->form = $form;
        }
    }

    public function editarAction() {
        $remover_imagem = $this->getRequest()->getParam('remove');

        $form = new UploadForm();

        if ($remover_imagem > 0) {
            $cliente = $this->_clienteRepository->getById($remover_imagem);

            $data = $this->getRequest()->getPost();
            $this->_setData($cliente, $data);

            $this->_deleteImage($cliente);

            $this->_helper->FlashMessenger(array('info' => 'Imagem do perfil removida com sucesso.'));
            $this->view->form = $form;
            $this->view->cliente = $cliente;
            $this->view->procedimentos = $this->_procedimentoRepository->getProcedimentoByCliente($cliente->id);
        } else {
            $id = $this->getRequest()->getParam('id');
            $cliente = $this->_clienteRepository->getById($id);


            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($cliente, $data);

                $validate .= $this->_validate($cliente, $data);
                if (!isset($validate) || $validate == "") {
                    $cliente->save();
                    $this->_saveImage($cliente, $form, $data);

                    $this->_helper->FlashMessenger('Cliente alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/funcionario/cliente/?nolayout=' . $this->_layout);
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->cliente = $cliente;
                    $this->view->procedimentos = $this->_procedimentoRepository->getProcedimentoByCliente($cliente->id);
                }
            } else {
                $this->view->form = $form;
                $this->view->cliente = $cliente;
                $this->view->procedimentos = $this->_procedimentoRepository->getProcedimentoByCliente($cliente->id);

            }
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $cliente = $this->_clienteRepository->getById($id, $this->_empresa_id);

        if(count($cliente->Procedimento) <= 0) {
            if ($cliente != FALSE) {
                $cliente->status = Cliente::EXCLUIDO;
                $cliente->save();

                $this->_helper->FlashMessenger('Cliente removido com sucesso.');
            } else {
                $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um cliente que não existe.'));
            }
        }else{
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um cliente que possui mais mais registros vinculados a ele.'));

        }
        $this->_redirect($this->view->baseUrl() . '/funcionario/cliente/?nolayout=' . $this->_layout);
    }



    private function _setData(Cliente $cliente, $data) {
        $caracter_remove = array("(", ")", "-", "/", " ", ".");
        $cliente->nome = AppUtil::setFirstUpWord($data['nome']);
        $cliente->sobrenome = AppUtil::setFirstUpWord($data['sobrenome']);
        $cliente->email = strtolower($data['email']);
        $cliente->status = $data['status'];
        $cliente->empresa_id = $this->_empresa_id;
        $cliente->cpf = str_replace($caracter_remove, "", $data['cpf']);
        $cliente->data_aniversario = AppUtil::convertStringToDate($data['data_aniversario']);
        $cliente->observacao = $data['observacao'];


        if ($cliente->Endereco[0]->id <= 0) {
            $cliente_endereco = new Endereco();
            $this->_setDataEndereco($cliente_endereco, $data);
            $cliente->Endereco[0] = $cliente_endereco;
        } else {
            $cliente_endereco = $cliente->Endereco[0];
            $this->_setDataEndereco($cliente_endereco, $data);
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



    private function _validate(Cliente $cliente, $data) {
        if ($cliente->nome == "")
            $result .= "<li>O campo <b>Nome </b> deve ser informado.</li>";

        if ($cliente->sobrenome == "")
            $result .= "<li>O campo <b>Sobrenome </b> deve ser informado.</li>";

            //$result .= $this->_validateEmail($cliente);

        if ($cliente->status <= 0)
            $result .= "<li>O campo <b>Status</b> deve ser informado.</li>";

        //if ($cliente->data_aniversario == "")
           // $result .= "<li>O campo <b>Data de Aniversário</b> deve ser informado.</li>";

        if ($data['telefone_1'] == "")
            $result .= "<li>O campo <b>Celular 1</b> deve ser informado.</li>";

        return $result;
    }

    private function _validateEmail(Cliente $cliente) {
        if ($cliente->email == "") {
            $result .= "<li>O campo <b>Email</b> deve ser informado.</li>";
        } else if ($this->_clienteRepository->isExiste($cliente, $this->_empresa_id)) {
            $result .= "<li>O <b>Email</b> informado já esta registrado.</li>";
        }

        return $result;
    }



    private function _getFileName(Cliente $cliente) {
        chdir(APPLICATION_UPLOAD_PATH . '/c');
        return getcwd() . '/' . AppUtil::getFileName($cliente);
    }

    private function _saveImage(Cliente $cliente, $form, $data) {
        if ($form->isValid($data)) {
            $form->getValues();

            $filename = $form->file->getFileName(null, false);
            $filename = getcwd() . '/' . $filename;

            $filename_new = $this->_getFileName($cliente);

            copy($filename, $filename_new);
            unlink($filename);
        }
    }

    private function _deleteImage(Cliente $cliente) {
        $filename = $this->_getFileName($cliente);
        unlink($filename);
    }



    private function _sendMail(Cliente $cliente) {

        $app_email = Zend_Registry::getInstance()->get('email');
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('cliente', $cliente);
        $html->assign('empresa', $cliente->Empresa);
        $html->assign('remetente', $app_email->mail->de);


        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = $cliente->email;

        $subject = "Seja Bem Vinda ao Espaço ELA";
        $body = $html->render('usuario_cadastro.phtml');

        EmailUtil::send($from, $to, $subject, $body);
    }

}
