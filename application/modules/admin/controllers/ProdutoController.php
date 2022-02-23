<?php

class Admin_ProdutoController extends Zend_Controller_Action {

    private $_produtoRepository;
    private $_produtoCategoriaRepository;
    private $_empresa_id;

    public function init() {
        $this->_produtoRepository = new ProdutoRepository();
        $this->_produtoCategoriaRepository = new ProdutoCategoriaRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addSelectFilter('produto_categoria_id', $params['categoria']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'codigo';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_produtoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(20);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->produto_categorias = $this->_produtoCategoriaRepository->getList($this->_empresa_id);
    }

    public function novoAction() {
        $form = new UploadForm();

        if ($this->getRequest()->isPost()) {
            $produto = new Produto();

            $data = $this->getRequest()->getPost();

            $this->_setData($produto, $data);
            $this->_setCodigo($produto);

            $validate = $this->_validate($produto);
            if (!isset($validate) || $validate == "") {

                $produto->save();
                $this->_saveImage($produto, $form, $data);


                $this->_helper->FlashMessenger('Produto cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/produto/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->form = $form;
                $this->view->produto = $produto;
                $this->view->produtoCategoria = $this->_produtoCategoriaRepository->getList($this->_empresa_id);
            }
        } else {
            $this->view->form = $form;
            $this->view->produtoCategoria = $this->_produtoCategoriaRepository->getList($this->_empresa_id);
        }
    }

    public function editarAction() {
        $remover_imagem = $this->getRequest()->getParam('remove');

        $form = new UploadForm();

        if ($remover_imagem > 0) {
            $produto = $this->_produtoRepository->getById($remover_imagem);

            $data = $this->getRequest()->getPost();
            $this->_setData($produto, $data);

            $this->_deleteImage($produto);

            $this->_helper->FlashMessenger(array('info' => 'Foto do produto removido com sucesso.'));
            $this->view->form = $form;
            $this->view->produto = $produto;
            $this->view->produtoCategoria = $this->_produtoCategoriaRepository->getList($this->_empresa_id);
        } else {
            $id = $this->getRequest()->getParam('id');
            $produto = $this->_produtoRepository->getById($id, $this->_empresa_id);

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($produto, $data);

                $validate = $this->_validate($produto);
                if (!isset($validate) || $validate == "") {
                    $produto->save();
                    $this->_saveImage($produto, $form, $data);

                    $this->_helper->FlashMessenger('Produto alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/produto/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->produto = $produto;
                    $this->view->produtoCategoria = $this->_produtoCategoriaRepository->getList($this->_empresa_id);
                }
            } else {
                $this->view->form = $form;
                $this->view->produto = $produto;
                $this->view->produtoCategoria = $this->_produtoCategoriaRepository->getList($this->_empresa_id);
            }
        }
    }



    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $produto = $this->_produtoRepository->getById($id, $this->_empresa_id);
        if ($produto != FALSE) {

            if (count($produto->Lancamento) >= 1) {
                $this->_helper->FlashMessenger(array('warning' => 'Este Produto possui lançamentos financeiros que estão em vigor. <a onclick="return confirm(\'Confirma a exclusão deste produto mesmo assim ?\')" href="' . $this->view->baseUrl() . '/admin/produto/deletar-forcado/id/' . $id . '">[ Forçar exclusão ]</a>'));
                $this->_redirect($this->view->baseUrl() . '/admin/produto/');
            } else {
                $produto->Endereco->delete();
                $produto->delete();
                $this->_helper->FlashMessenger('Produto removido com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/produto/');
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um produto que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/produto/');
        }
    }

    public function deletarForcadoAction() {
        $id = $this->getRequest()->getParam('id');
        $produto = $this->_produtoRepository->getById($id, $this->_empresa_id);
        if ($produto != FALSE) {
            foreach ($produto->Lancamento as $lancamento) {
                $lancamento->produto_id = NULL;
                $lancamento->save();
            }
            $produto->delete();
            $this->_helper->FlashMessenger('Produto removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um produto que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/produto/');
    }

    private function _setData(Produto $produto, $data) {

        $produto->marca = AppUtil::setFirstUpWord($data['marca']);
        $produto->modelo = AppUtil::setFirstUpWord($data['modelo']);
        $produto->curvatura = $data['curvatura'];
        $produto->espessura =  AppUtil::setFirstUpWord($data['espessura']);
        $produto->produto_categoria_id = $data['produto_categoria'];
        $produto->qt_minima = $data['qt_minima'];
        $produto->status = $data['status'];
        $produto->tamanho = $data['tamanho'];
        $produto->empresa_id = $this->_empresa_id;
        if($produto->produto_categoria_id == 1){
            $produto->descricao = $produto->marca." ".$produto->espessura;
        }else {
            $produto->descricao = AppUtil::setFirstUpWord($data['descricao']);
        }

    }



    private function _setCodigo(Produto $produto){
        $produto_obj_total = $this->_produtoRepository->getTotalProdutosByCategoria($produto->produto_categoria_id,$this->_empresa_id);
        $cod = str_pad($produto_obj_total, 2, "0", STR_PAD_LEFT);

        $produto->codigo = $produto->produto_categoria_id . "" . $cod;
    }

    private function _validate(Produto $produto) {
        $result .= $this->_validateDescricao($produto);

        if ($produto->produto_categoria_id <= 0)
            $result .= "<li>O campo <b>Categoria </b> deve ser informado.</li>";


        return $result;
    }

    private function _validateDescricao(Produto $produto) {
        if ($produto->descricao == "") {
            $result .= "<li>O campo <b>Descricao</b> deve ser informado.</li>";
        } else if ($this->_produtoRepository->isExiste($produto, $this->_empresa_id)) {
            $result .= "<li>A <b>Descrição</b> informada já esta registrado.</li>";
        }

        return $result;
    }


    private function _saveImage(Produto $produto, $form, $data) {
        if ($form->isValid($data)) {
            $form->getValues();

            $filename = $form->file->getFileName(null, false);
            $filename = getcwd() . '/' . $filename;

            $filename_new = $this->_getFileName($produto);

            copy($filename, $filename_new);
            unlink($filename);
        }
    }

    private function _deleteImage(Produto $produto) {
        $filename = $this->_getFileName($produto);
        unlink($filename);
    }

    private function _getFileName(Produto $produto) {
        chdir(APPLICATION_UPLOAD_PATH . '/pr');
        return getcwd() . '/' . AppUtil::getFileName($produto);
    }




}

