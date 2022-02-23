<?php

class Admin_EmpresaController extends Zend_Controller_Action
{

    private $_empresaRepository;
    private $_empresa;

    public function init() {
        $this->_empresa = SessionUtil::getEmpresaSession();
        $this->_empresaRepository = new EmpresaRepository();

    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $filter = new RepositoryFilter($params);
        $filter->addTextFilter('razao_social', $params['razao_social']);
        $filter->addTextFilter('nome_fantasia', $params['nome_fantasia']);
        $filter->addTextFilter('cnpj', $params['cnpj']);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addFilter('status != ?', Empresa::EXCLUIDO);


        $sortParam = ($params["sort"]) ? $params["sort"] : 'id';
        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_empresaRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(10);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
    }

    public function novoAction() {
        $form = new UploadForm();

        if ($this->getRequest()->isPost()) {
            $empresa = new Empresa();

            $data = $this->getRequest()->getPost();

            $this->_setata($empresa, $data);

            $validate = $this->_validate($empresa);

            if (!isset($validate) || $validate == "") {

                $empresa->save();
                $this->_saveImage($empresa, $form, $data);

                $this->_helper->FlashMessenger('Empresa cadastrada com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/empresa/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->form = $form;
                $this->view->empresa = $empresa;
            }
        } else {
            $this->view->form = $form;
        }
    }


    public function editarAction() {
        $remover_imagem = $this->getRequest()->getParam('remove');


        $form = new UploadForm();

        if ($remover_imagem > 0) {
            $empresa = $this->_empresaRepository->getById($remover_imagem);

            $data = $this->getRequest()->getPost();
            $this->_setData($empresa, $data);

            $this->_deleteImage($empresa);

            $this->_helper->FlashMessenger(array('info' => 'Logomarca removida com sucesso.'));
            $this->view->form = $form;
            $this->view->empresa = $empresa;
        } else {
            $id = $this->getRequest()->getParam('id');
            $empresa = $this->_empresaRepository->getById($id);

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($empresa, $data);

                $validate = $this->_validate($empresa);
                if (!isset($validate) || $validate == "") {
                    $empresa->save();
                    $this->_saveImage($empresa, $form, $data);

                    $this->_helper->FlashMessenger('Empresa alterada com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/empresa/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->empresa = $empresa;
                }
            } else {
                $this->view->form = $form;
                $this->view->empresa = $empresa;
            }
        }
    }


    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $empresa = $this->_empresaRepository->getById($id);

        $empresa->status = Empresa::EXCLUIDO;
        $empresa->save();

       // $this->_empresaRepository->remove($id);
        $this->_helper->FlashMessenger('Empresa removida com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/admin/empresa/');
    }

    private function _setData(Empresa $empresa, $data) {
        $caracter_remove = array("(", ")", "-", "/", " ", ".");

        $empresa->razao_social = $data['razao_social'];
        $empresa->nome_fantasia = $data['nome_fantasia'];
        $empresa->cnpj = str_replace($caracter_remove, "", $data['cnpj']);
        $empresa->status = $data['status'];

        if ($empresa->Endereco[0]->id <= 0) {
            $empresa_endereco = new Endereco();
            $this->_setDataEndereco($empresa_endereco, $data);
            $empresa->Endereco[0] = $empresa_endereco;
        } else {
            $empresa_endereco = $empresa->Endereco[0];
            $this->_setDataEndereco($empresa_endereco, $data);
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
        $endereco->empresa_id = $this->_empresa;
        $endereco->telefone_1 = str_replace($caracter_remove, "", $data['telefone_1']);
        $endereco->telefone_2 = str_replace($caracter_remove, "", $data['telefone_2']);
    }

    private function _validate(Empresa $empresa) {
        if ($empresa->razao_social == "")
            $result .= "<li>O campo <b>Razão Social </b> deve ser informado.</li>";

        if ($empresa->nome_fantasia == "")
            $result .= "<li>O campo <b>Nome Fantasia </b> deve ser informado.</li>";

        if ($empresa->cnpj == "")
            $result .= "<li>O campo <b>CNPJ</b> deve ser informado.</li>";

        if ($empresa->id <= 0)
            $result .= $this->_validateCNPJ($empresa);

        return $result;
    }

    private function _validateCNPJ(Empresa $empresa) {
        $empresa_db = $this->_empresaRrepository->getByCNPJ($empresa->cnpj);

        if ($empresa_db->id > 0)
            return $result .= "<li>O <b>CNPJ</b> informado já esta registrado.</li>";

        return "";
    }

    private function _getFileName(Empresa $empresa) {
        chdir(APPLICATION_UPLOAD_PATH . '/e');
        return getcwd() . '/' . AppUtil::getFileName($empresa);
    }

    private function _saveImage(Empresa $empresa, $form, $data) {
        if ($form->isValid($data)) {
            $form->getValues();

            $filename = $form->file->getFileName(null, false);
            $filename = getcwd() . '/' . $filename;

            $filename_new = $this->_getFileName($empresa);

            copy($filename, $filename_new);
            unlink($filename);
        }
    }

    private function _deleteImage(Empresa $empresa) {
        $filename = $this->_getFileName($empresa);
        unlink($filename);
    }

}