<?php

class Admin_PontoController extends Zend_Controller_Action {

    private $_registroPontoRepository;
    private $_usuarioRepository;
    private $_empresa_id;

    public function init() {
        $this->_registroPontoRepository = new RegistroPontoRepository();
        $this->_usuarioRepository = new UsuarioRepository();
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
        $filter->addSelectFilter('usuario_id', $params['funcionario']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'id';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_registroPontoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(20);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->funcionarios = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);

    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $registroPonto = new RegistroPonto();

            $data = $this->getRequest()->getPost();

            $this->_setData($registroPonto, $data);

            $validate = $this->_validate($registroPonto);
            if (!isset($validate) || $validate == "") {

                $registroPonto->save();

                $this->_helper->FlashMessenger('Ponto cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/ponto/?nolayout=' . $this->_layout);
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->registroPonto = $registroPonto;
                $this->view->funcionarios = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
                $this->view->meses = AppUtil::getMeses();
            }
        } else {
            $this->view->funcionarios = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
            $this->view->meses = AppUtil::getMeses();
        }
    }

    public function editarAction() {

            $id = $this->getRequest()->getParam('id');
        $registroPonto = $this->_registroPontoRepository->getById($id);

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($registroPonto, $data);

                $validate = $this->_validate($registroPonto);
                if (!isset($validate) || $validate == "") {
                    $registroPonto->save();

                    $this->_helper->FlashMessenger('Ponto alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/ponto/?nolayout=' . $this->_layout);
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->registroPonto = $registroPonto;
                    $this->view->funcionarios = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
                    $this->view->meses = AppUtil::getMeses();
                }
            } else {
                $this->view->registroPonto = $registroPonto;
                $this->view->funcionarios = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
                $this->view->meses = AppUtil::getMeses();
            }

    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $registroPonto = $this->_registroPontoRepository->getById($id, $this->_empresa_id);
        if ($registroPonto != FALSE) {
            $registroPonto->save();

            $this->_helper->FlashMessenger('Ponto removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um registro de ponto que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/ponto/?nolayout=' . $this->_layout);
    }


    public function registrarAction() {
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getPost();

            $email = $data['usuario_email'];
            $senha = $data['senha_ponto'];

            if (!isset($email) || $email == '' || !isset($senha) || $senha == '') {
                $this->_helper->FlashMessenger(array('error' => 'Os campos para registro de ponto devem ser preenchidos.'));
            }
            $id = SessionUtil::getIdUser();
            $user = $this->_usuarioRepository->getById($id);

            $validate = $this->_validetUser($user, $data);

            if (!isset($validate) || $validate == "") {

                $registroPonto = new RegistroPonto();
                $this->_setData($registroPonto, $user);

                $registroPonto->save();

                $this->_helper->FlashMessenger('Ponto salvo com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/funcionario/perfil/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
            }
        }
    }


    private function _validetUser($user, $data){

        if($user->email != $data['usuario_email'])
            $result .= "<li>O <b>Email </b> está errado</li>";


        if($user->senha != $data['senha_ponto'])
            $result .= "<li>A <b>Senha</b> não está correta.</li>";

        return $result;

    }


    private function _setData(RegistroPonto $registroPonto, $user){
        $registroPonto->ponto = date('Y-m-d H:i:s');
        $registroPonto->usuario_id = $user->id;
        $registroPonto->empresa_id = $user->empresa_id;
        $registroPonto->status = RegistroPonto::ATIVO;
    }



}
