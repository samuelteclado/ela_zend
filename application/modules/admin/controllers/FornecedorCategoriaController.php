<?php

class Admin_FornecedorCategoriaController extends Zend_Controller_Action {

    private $_fornecedorCategoriaRepository;
    private $_empresa_id;

    public function init() {
        $this->_fornecedorCategoriaRepository = new FornecedorCategoriaRepository();
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


        $sortParam = ($params["sort"]) ? $params["sort"] : 'descricao';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_fornecedorCategoriaRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $fornecedor_categoria = new FornecedorCategoria();

            $data = $this->getRequest()->getPost();

            $this->_setData($fornecedor_categoria, $data);

            $validate = $this->_validate($fornecedor_categoria);
            if (!isset($validate) || $validate == "") {
                $fornecedor_categoria->save();

                $this->_helper->FlashMessenger('Categoria cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/fornecedor-categoria/?nolayout=' . $this->_layout);
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->fornecedor_categoria = $fornecedor_categoria;
            }
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $fornecedor_categoria = $this->_fornecedorCategoriaRepository->getById($id, $this->_empresa_id);

        if ($fornecedor_categoria != FALSE) {


            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();

                $this->_setData($fornecedor_categoria, $data);

                $validate = $this->_validate($fornecedor_categoria);
                if (!isset($validate) || $validate == "") {
                    $fornecedor_categoria->save();

                    $this->_helper->FlashMessenger('Categoria alterada com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/fornecedor-categoria/?nolayout=' . $this->_layout);
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->fornecedor_categoria = $fornecedor_categoria;
                }
            } else {

                $this->view->fornecedor_categoria = $fornecedor_categoria;
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando acessar uma categoria de fornecedores que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/fornecedor-categoria/?nolayout=' . $this->_layout);
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $fornecedor_categoria = $this->_fornecedorCategoriaRepository->getById($id, $this->_empresa_id);
        if ($fornecedor_categoria != FALSE) {


            if ($fornecedor_categoria->Fornecedor->count() > 0) {
                $this->_helper->FlashMessenger(array('warning' => '<b>' . $fornecedor_categoria->descricao . '</b> não pode ser removido, pois esta vinculado a um fornecedor'));
            } else {
                $fornecedor_categoria->delete();
            }

            $this->_helper->FlashMessenger('Categoria removida com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um registro de notas que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/fornecedor-categoria/?nolayout=' . $this->_layout);
    }

    private function _setData(FornecedorCategoria $fornecedor_categoria, $data) {

        $fornecedor_categoria->descricao = $data['descricao'];
        $fornecedor_categoria->empresa_id = $this->_empresa_id;
    }

    private function _validate(FornecedorCategoria $fornecedor_categoria) {
        if ($fornecedor_categoria->descricao == "")
            $result .= "<li>O campo <b>Nome</b> deve ser informado.</li>";

        $result .= $this->_validateDescricao($fornecedor_categoria);

        return $result;
    }

    private function _validateDescricao(FornecedorCategoria $fornecedor_categoria) {
        $categoria_db = $this->_fornecedorCategoriaRepository->getByDescricao($fornecedor_categoria->descricao, $this->_empresa_id);


        if ($categoria_db->descricao == $fornecedor_categoria->descricao && $fornecedor_categoria->id == NULL)
            return $result .= "<li>A <b>Descrição</b> informada já esta registrada.</li>";

        return "";
    }

}
