<?php

class Admin_UsuarioFuncionalidadeController extends Zend_Controller_Action {

    private $_usuarioFuncionalidadeRepository;

    public function init() {
        $this->_usuarioFuncionalidadeRepository = new UsuarioGrupoPermissaoFuncionalidadeRepository();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addTextFilter('nome', $params['nome']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'nome';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_usuarioFuncionalidadeRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(15);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $usuario_funcionalidade = new UsuarioGrupoPermissaoFuncionalidade();

            $data = $this->getRequest()->getPost();

            $this->_setData($usuario_funcionalidade, $data);

            $validate = $this->_validate($usuario_funcionalidade);
            if (!isset($validate) || $validate == "") {

                $usuario_funcionalidade->save();

                $this->_helper->FlashMessenger('Funcionalidade cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/usuario-funcionalidade/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->usuario_funcionalidade = $usuario_funcionalidade;
            }
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $usuario_funcionalidade = $this->_usuarioFuncionalidadeRepository->getById($id);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $this->_setData($usuario_funcionalidade, $data);

            $validate = $this->_validate($usuario_funcionalidade, $data);
            if (!isset($validate) || $validate == "") {
                $usuario_funcionalidade->save();

                $this->_helper->FlashMessenger('Funcionalidade alterada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/usuario-funcionalidade/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
            }
        }

        $this->view->usuario_funcionalidade = $usuario_funcionalidade;
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');
        $usuario_funcionalidade = $this->_usuarioFuncionalidadeRepository->getById($id);

        if ($usuario_funcionalidade->UsuarioGrupoPermissao->count() > 0) {
            $this->_helper->FlashMessenger(array('warning' => 'A funcionalidade não pode ser removido, pois esta vinculada a um grupo. <a onclick="return confirm(\'Confirma a exclusão da funcionalidade, todos os vinculos com os grupos serão perdidos ?\')" href="' . $this->view->baseUrl() . '/admin/usuario-funcionalidade/deletar-forcado/id/' . $id . '">[ Forçar exclusão ]</a>'));
        } else {
            $usuario_funcionalidade->delete();
            $this->_helper->FlashMessenger('Funcionalidade removida com sucesso.');
        }

        $this->_redirect($this->view->baseUrl() . '/admin/usuario-funcionalidade/');
    }

    public function deletarForcadoAction() {
        $id = $this->getRequest()->getParam('id');
        $usuario_funcionalidade = $this->_usuarioFuncionalidadeRepository->getById($id);

        foreach ($usuario_funcionalidade->UsuarioGrupoPermissao as $usuario_permissao)
            $usuario_permissao->delete();

        $usuario_funcionalidade->delete();

        $this->_helper->FlashMessenger('Funcionalidade removida com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/usuario-funcionalidade/');
    }

    private function _setData(UsuarioGrupoPermissaoFuncionalidade $usuario_funcionalidade, $data) {

        $usuario_funcionalidade->nome = $data['nome'];
        $usuario_funcionalidade->module = $data['modulo'];
        $usuario_funcionalidade->controller = $data['controlador'];
        $usuario_funcionalidade->action = $data['acao'];
    }

    private function _validate(UsuarioGrupoPermissaoFuncionalidade $usuario_funcionalidade) {
        if ($usuario_funcionalidade->nome == "")
            $result .= "<li>O campo <b>Nome</b> deve ser informado.</li>";

        if ($usuario_funcionalidade->module == "")
            $result .= "<li>O campo <b>Module</b> deve ser informado.</li>";

        if ($usuario_funcionalidade->controller == "")
            $result .= "<li>O campo <b>Controller</b> deve ser informado.</li>";

        if ($usuario_funcionalidade->action == "")
            $result .= "<li>O campo <b>Action</b> deve ser informado.</li>";

        return $result;
    }

}
