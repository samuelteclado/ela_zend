<?php

class Admin_UsuarioGrupoController extends Zend_Controller_Action {

    private $_usuarioGrupoRepository;
    private $_usuarioFuncionalidadeRepository;
    private $_empresaRepository;

    public function init() {
        $this->_usuarioGrupoRepository = new UsuarioGrupoRepository();
        $this->_usuarioFuncionalidadeRepository = new UsuarioGrupoPermissaoFuncionalidadeRepository();
        $this->_eempresaRepository = new EmpresaRepository();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addTextFilter('descricao', $params['descricao']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'descricao';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_usuarioGrupoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $usuario_grupo = new UsuarioGrupo();

            $data = $this->getRequest()->getPost();

            $this->_setData($usuario_grupo, $data);

            $validate = $this->_validate($usuario_grupo);
            if (!isset($validate) || $validate == "") {

                $usuario_grupo->save();

                $this->_helper->FlashMessenger('Grupo de usuários cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/usuario-grupo/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->usuario_grupo = $usuario_grupo;
            }
        }

        $this->view->funcionalidades = $this->_usuarioFuncionalidadeRepository->getList();
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $usuario_grupo = $this->_usuarioGrupoRepository->getById($id);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();

            $this->_setData($usuario_grupo, $data);

            $validate = $this->_validate($usuario_grupo, $data);
            if (!isset($validate) || $validate == "") {
                $usuario_grupo->save();

                $this->_helper->FlashMessenger('Grupo de usuários alterado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/usuario-grupo/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
            }
        }

        $this->view->usuario_grupo = $usuario_grupo;
        $this->view->funcionalidades = $this->_usuarioFuncionalidadeRepository->getList();
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');
        $usuario_grupo = $this->_usuarioGrupoRepository->getById($id);

        if ($usuario_grupo->Usuario->count() > 0) {
            $this->_helper->FlashMessenger(array('warning' => 'O grupo de usuários não pode ser removido, pois esta vinculada a um usuário.'));
        } else {
            foreach ($usuario_grupo->UsuarioGrupoPermissao as $usuario_permissao)
                $usuario_permissao->delete();

            $usuario_grupo->delete();
            
            $this->_helper->FlashMessenger('Grupo de usuários removido com sucesso.');
        }

        $this->_redirect($this->view->baseUrl() . '/admin/usuario-grupo/');
    }

    private function _setData(UsuarioGrupo $usuario_grupo, $data) {

        $usuario_grupo->descricao = $data['descricao'];

        $this->_setPermissoes($usuario_grupo, $data);
    }

    private function _setPermissoes(UsuarioGrupo $usuario_grupo, $data) {
        $usuario_grupo->UsuarioGrupoPermissao->clear();

        foreach ($data['funcionalidade'] as $funcionalidade) {
            $usuarioGrupoPermissao = new UsuarioGrupoPermissao();
            $usuarioGrupoPermissao->usuario_grupo_id = $usuario_grupo->id;
            $usuarioGrupoPermissao->usuario_grupo_permissao_funcionalidade_id = $funcionalidade;
            $usuarioGrupoPermissao->permitido = TRUE;
            $usuario_grupo->UsuarioGrupoPermissao->add($usuarioGrupoPermissao);
        }
    }

    private function _validate(UsuarioGrupo $usuario_grupo) {
        if ($usuario_grupo->descricao == "")
            $result .= "<li>O campo <b>Descrição</b> deve ser informado.</li>";

        return $result;
    }

}
