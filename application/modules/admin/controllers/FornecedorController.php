<?php

class Admin_FornecedorController extends Zend_Controller_Action {

    private $_fornecedorRepository;
    private $_fornecedorCategoriaRepository;
    private $_empresa_id;

    public function init() {
        $this->_fornecedorRepository = new FornecedorRepository();
        $this->_fornecedorCategoriaRepository = new FornecedorCategoriaRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addFilter('empresa_id = ?', $this->_empresa_id);
        $filter->addTextFilter('descricao', $params['descricao']);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addSelectFilter('fornecedor_categoria_id', $params['categoria']);

        $sortParam = ($params["sort"]) ? $params["sort"] : 'descricao';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_fornecedorRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(20);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->fornecedores_categorias = $this->_fornecedorCategoriaRepository->getList($this->_empresa_id);
    }

    public function novoAction() {

        if ($this->getRequest()->isPost()) {
            $fornecedor = new Fornecedor();

            $data = $this->getRequest()->getPost();

            $this->_setData($fornecedor, $data);

            $validate = $this->_validate($fornecedor);
           // $validate .= $this->_validateContato($fornecedor);
            if (!isset($validate) || $validate == "") {

                $fornecedor->save();

                $this->_helper->FlashMessenger('Fornecedor cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->fornecedor = $fornecedor;
            }
        }
        $this->view->fornecedorcategoria = $this->_fornecedorCategoriaRepository->getList($this->_empresa_id);
    }

    public function editarAction() {

        $id = $this->getRequest()->getParam('id');
        $fornecedor = $this->_fornecedorRepository->getById($id, $this->_empresa_id);

        if ($fornecedor != FALSE) {
            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($fornecedor, $data);

                $validate = $this->_validate($fornecedor);
                if (!isset($validate) || $validate == "") {
                    $fornecedor->save();

                    $this->_helper->FlashMessenger('Fornecedor alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                }
            }
            $this->view->fornecedor = $fornecedor;
            $this->view->fornecedorcategoria = $this->_fornecedorCategoriaRepository->getList($this->_empresa_id);
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando acessar um fornecedor que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $fornecedor = $this->_fornecedorRepository->getById($id, $this->_empresa_id);
        if ($fornecedor != FALSE) {

            if (count($fornecedor->Lancamento) >= 1) {
                $this->_helper->FlashMessenger(array('warning' => 'Este fornecedor possui lançamentos financeiros que estão em vigor. <a onclick="return confirm(\'Confirma a exclusão deste fornecedor mesmo assim ?\')" href="' . $this->view->baseUrl() . '/secretaria/fornecedor/deletar-forcado/id/' . $id . '">[ Forçar exclusão ]</a>'));
                $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
            } else {
                $fornecedor->Endereco->delete();
                $fornecedor->delete();
                $this->_helper->FlashMessenger('Fornecedor removido com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
            }
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um fornecedor que não existe.'));
            $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
        }
    }

    public function deletarForcadoAction() {
        $id = $this->getRequest()->getParam('id');
        $fornecedor = $this->_fornecedorRepository->getById($id, $this->_empresa_id);
        if ($fornecedor != FALSE) {
            foreach ($fornecedor->Lancamento as $lancamento) {
                $lancamento->fornecedor_id = NULL;
                $lancamento->save();
            }
            $fornecedor->delete();
            $this->_helper->FlashMessenger('Fornecedor removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um fornecedor que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/fornecedor/');
    }

    private function _setData(Fornecedor $fornecedor, $data) {
        $caracter_remove = array("(", ")", "-", " ", ".", "/");
        $fornecedor->descricao = AppUtil::setFirstUpWord($data['descricao']);
        $fornecedor->cnpj = str_replace($caracter_remove, "", $data['cnpj']);
        $fornecedor->contato = $data['contato'];
        $fornecedor->status = $data['status'];
        $fornecedor->email = $data['email'];
        $fornecedor->fornecedor_categoria_id = $data['categoria'];
        $fornecedor->empresa_id = $this->_empresa_id;

        if ($fornecedor->Endereco[0]->id <= 0) {
            $endereco = new Endereco();
            $this->_setDataEndereco($endereco, $data);
            $fornecedor->Endereco[0] = $endereco;
        } else {
            $endereco = $fornecedor->Endereco[0];
            $this->_setDataEndereco($endereco, $data);
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

    private function _validate(Fornecedor $fornecedor) {
        if ($fornecedor->descricao == "")
            $result .= "<li>O campo <b>Descrição </b> deve ser informado.</li>";

        if ($fornecedor->fornecedor_categoria_id <= 0)
            $result .= "<li>O campo <b>Categoria </b> deve ser informado.</li>";

        return $result;
    }



}
