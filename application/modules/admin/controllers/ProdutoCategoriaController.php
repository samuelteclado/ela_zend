<?php

class Admin_ProdutoCategoriaController extends Zend_Controller_Action {

    private $_produtoCategoriaRepositoy;
    private $_empresa_id;

    public function init() {
        $this->_produtoCategoriaRepositoy = new ProdutoCategoriaRepository();
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

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_produtoCategoriaRepositoy->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $produto_categoria = new ProdutoCategoria();

            $data = $this->getRequest()->getPost();

            $this->_setData($produto_categoria, $data);

            $validate = $this->_validate($produto_categoria);
            if (!isset($validate) || $validate == "") {
                $produto_categoria->save();

                $this->_helper->FlashMessenger('Categoria cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/produto-categoria/?nolayout=' . $this->_layout);
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->produto_categoria = $produto_categoria;
            }
        }
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $produto_categoria = $this->_produtoCategoriaRepositoy->getById($id, $this->_empresa_id);

        if ($produto_categoria != FALSE) {


            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();

                $this->_setData($produto_categoria, $data);

                $validate = $this->_validate($produto_categoria);
                if (!isset($validate) || $validate == "") {
                    $produto_categoria->save();

                    $this->_helper->FlashMessenger('Categoria alterada com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/produto-categoria/?nolayout=' . $this->_layout);
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->produto_categoria = $produto_categoria;
                }
            } else {

                $this->view->produto_categoria = $produto_categoria;
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando acessar uma categoria de produto que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/produto-categoria/?nolayout=' . $this->_layout);
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $produto_categoria = $this->_produtoCategoriaRepositoy->getById($id, $this->_empresa_id);
        if ($produto_categoria != FALSE) {


            if ($produto_categoria->Produto->count() > 0) {
                $this->_helper->FlashMessenger(array('warning' => '<b>' . $produto_categoria->descricao . '</b> não pode ser removido, pois esta vinculado a um produto'));
            } else {
                $produto_categoria->delete();
            }

            $this->_helper->FlashMessenger('Categoria removida com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um registro de categoria que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/produto-categoria/?nolayout=' . $this->_layout);
    }

    private function _setData(ProdutoCategoria $produto_categoria, $data) {

        $produto_categoria->descricao = $data['descricao'];
        $produto_categoria->status = $data['status'];
        $produto_categoria->empresa_id = $this->_empresa_id;
    }

    private function _validate(ProdutoCategoria $produto_categoria) {
        if ($produto_categoria->descricao == "")
            $result .= "<li>O campo <b>Nome</b> deve ser informado.</li>";

        $result .= $this->_validateDescricao($produto_categoria);

        return $result;
    }

    private function _validateDescricao(ProdutoCategoria $produto_categoria) {
        $categoria_db = $this->_produtoCategoriaRepositoy->getByDescricao($produto_categoria->descricao, $this->_empresa_id);


        if ($categoria_db->descricao == $produto_categoria->descricao && $produto_categoria->id == NULL)
            return $result .= "<li>A <b>Descrição</b> informada já esta registrada.</li>";

        return "";
    }

}
