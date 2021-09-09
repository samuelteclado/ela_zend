<?php

class Admin_ProcedimentoController extends Zend_Controller_Action {

    private $_procedimentoRepository;
    private $_clienteRepository;
    private $_pagamentoTipoRepository;
    private $_procedimentoTipoRepository;
    private $_usuarioRepository;
    private $_empresa_id;

    public function init() {
        $this->_procedimentoRepository = new ProcedimentoRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
        $this->_clienteRepository = new ClienteRepository();
        $this->_procedimentoTipoRepository = new ProcedimentoTipoRepository();
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $params['ano'] = ($params['ano']) ? $params['ano'] : date('Y');
        $params['mes'] = ($params['mes']) ? $params['mes'] : date('m');
        $ano = $params['ano'];
        $mes = $params['mes'];
        $ultimo_dia = date("d", mktime(0, 0, 0, ($mes + 1), 0, $ano));

        $filter = new RepositoryFilter($params);
        $filter->addGenericFilter('(data BETWEEN "' . date("$ano-$mes-01") . '" AND "' . date("$ano-$mes-$ultimo_dia") .'")');
        $filter->addSelectFilter('cliente_id', $params['cliente']);
        $filter->addLeftJoinFilter("p.ProcedimentoTipo pt");
        $filter->addLeftJoinFilter("p.Cliente c");
        $filter->addSelectFilter('pt.tipo', $params['tipo']);
        $filter->addDateFilter('data', $params['data']);
        if ($params['situacao'] == Lancamento::ABERTO) {
            $filter->addNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::LIQUIDADO) {
            $filter->addNotNullFilter('pagamento_data');
        } elseif ($params['situacao'] == Lancamento::VENCIDO) {
            $filter->addGenericFilter('(data < "' . date('Y-m-d') . '" AND vencimento_data_atualizada IS NULL ) OR (vencimento_data_atualizada < "' . date('Y-m-d') . '")');
            $filter->addNullFilter('pagamento_data');
        }


        $sortParam = ($params["sort"]) ? $params["sort"] : 'data';
       // $sortParam = ($sortParam == "data") ? 'CASE WHEN vencimento_data_atualizada IS NULL THEN data ELSE vencimento_data_atualizada END' : $sortParam;

        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $list = new Zend_Paginator(new My_Zend_Paginator_Adapter_Doctrine($this->_procedimentoRepository->getListByFilter($filter, $orderby)));
        $list->setItemCountPerPage(1000);
        $list->setCurrentPageNumber($params["page"]);
        $this->view->list = $list;
        $this->view->list_params = array('filter' => $filter);

        $orderby->addOrder($params["sort"], ($orderParam == 'ASC') ? 'DESC' : 'ASC');

        $this->view->repository_filter = $filter;
        $this->view->repository_order = $orderby;
        $this->view->clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);

        $this->view->params = $params;
        $this->view->meses = AppUtil::getMeses();
        $data_atual = date("Y-m-d");
        $this->view->data_atual = $data_atual;
    }

    public function novoAction() {
        $form = new UploadForm();

        if ($this->getRequest()->isPost()) {
            $procedimento = new Procedimento();

            $data = $this->getRequest()->getPost();

            $this->_setData($procedimento, $data);

            $validate = $this->_validate($procedimento);
            if (!isset($validate) || $validate == "") {

                $procedimento->save();
                $this->_saveImage($procedimento, $form, $data);


                $this->_helper->FlashMessenger('Procedimento cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/admin/procedimento/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->form = $form;
                $this->view->procedimento = $procedimento;
                $this->view->clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);
                $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);

            }
        } else {
            $this->view->clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);
            $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id);
            $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
            $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
            $this->view->form = $form;
        }
    }

    public function editarAction() {
        $remover_imagem = $this->getRequest()->getParam('remove');

        $form = new UploadForm();

        if ($remover_imagem > 0) {
            $procedimento = $this->_procedimentoRepository->getById($remover_imagem);

            $data = $this->getRequest()->getPost();
            $this->_setData($procedimento, $data);

            $this->_deleteImage($procedimento);

            $this->_helper->FlashMessenger(array('info' => 'Imagem do perfil removida com sucesso.'));
            $this->view->form = $form;
            $this->view->procedimento = $procedimento;
        } else {
            $id = $this->getRequest()->getParam('id');
            $procedimento = $this->_procedimentoRepository->getById($id);

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($procedimento, $data);

                $validate .= $this->_validate($procedimento);
                if (!isset($validate) || $validate == "") {
                    $procedimento->save();
                    $this->_saveImage($procedimento, $form, $data);

                    $this->_helper->FlashMessenger('Procedimento alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/admin/procedimento/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->procedimento = $procedimento;
                    $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
                    $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id);
                    $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                    $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
                }
            } else {
                $this->view->form = $form;
                $this->view->procedimento = $procedimento;
                $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
                $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByEmpresa($this->_empresa_id);
            }
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $procedimento = $this->_procedimentoRepository->getById($id, $this->_empresa_id);
        if ($procedimento != FALSE) {
            $procedimento->status = Procedimento::EXCLUIDO;
            $procedimento->save();

            $this->_helper->FlashMessenger('Procedimento removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um procedimento que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/admin/procedimento/');
    }

    private function _setData(Procedimento $procedimento, $data) {
        $procedimento->data = AppUtil::convertStringToDate($data['data']);
        $procedimento->horario = $data['horario'];
        $procedimento->status = $data['status'];
        $procedimento->valor = AppUtil::convertStringToFloat($data['valor']);
        $procedimento->observacao = $data['observacao'];
        $procedimento->data_confirmacao = AppUtil::convertStringToDate($data['data_confirmacao']);
        $procedimento->pagamento_tipo_id = $data['pagamento'];
        $procedimento->cliente_id = $data['cliente'];
        $procedimento->procedimento_tipo_id = $data['procedimento'];
        $procedimento->usuario_id = $data['colaborador'];
        $procedimento->empresa_id = $this->_empresa_id;

        if ($procedimento->ProcedimentoDetalhe[0]->id <= 0) {
            $procedimento_detalhe = new ProcedimentoDetalhe();
            $this->_setDataDetalhe($procedimento_detalhe, $data);
            $procedimento->ProcedimentoDetalhe[0] = $procedimento_detalhe;
        } else {
            $procedimento_detalhe = $procedimento->ProcedimentoDetalhe[0];
            $this->_setDataDetalhe($procedimento_detalhe, $data);
        }

    }

    private function _setDataDetalhe(ProcedimentoDetalhe $procedimento_detalhe, $data) {
        $procedimento_detalhe->maquiagem = $data['maquiagem'];
        $procedimento_detalhe->gestante = $data['gestante'];
        $procedimento_detalhe->procedimento_recente = $data['procedimento_recente'];
        $procedimento_detalhe->lente_contato = $data['lente_contato'];
        $procedimento_detalhe->alergia = $data['alergia'];
        $procedimento_detalhe->alergia_outros = $data['alergia_outros'];
        $procedimento_detalhe->problema_ocular = $data['problema_ocular'];
        $procedimento_detalhe->tireoide = $data['tireoide'];
        $procedimento_detalhe->tireoide_outros = $data['tireoide_outros'];
        $procedimento_detalhe->oncologico = $data['oncologico'];
        $procedimento_detalhe->procedimento_detalhecol = $data['procedimento_detalhecol'];
        $procedimento_detalhe->dorme_lado = $data['dorme_lado'];
        $procedimento_detalhe->dorme_esquerdo_direito = $data['dorme_esquerdo_direito'];
        $procedimento_detalhe->problema = $data['problema'];
        $procedimento_detalhe->modelo_fios = $data['modelo_fios'];
        $procedimento_detalhe->espessura = $data['espessura'];
        $procedimento_detalhe->curvatura = $data['curvatura'];
        $procedimento_detalhe->adesivo_cola = $data['adesivo_cola'];
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
        $endereco->empresa_id = $this->_empresa_id;
        $endereco->telefone_1 = str_replace($caracter_remove, "", $data['telefone_1']);
        $endereco->telefone_2 = str_replace($caracter_remove, "", $data['telefone_2']);

    }



    private function _validate(Procedimento $procedimento) {
        if ($procedimento->data == "")
            $result .= "<li>O campo <b>Data </b> deve ser informado.</li>";

        if ($procedimento->horario == "")
            $result .= "<li>O campo <b>Horário </b> deve ser informado.</li>";

        if ($procedimento->cliente_id <= 0)
            $result .= "<li>O campo <b>Cliente </b> deve ser informado.</li>";

        if ($procedimento->procedimento_tipo_id <= 0)
            $result .= "<li>O campo <b>Procedimento </b> deve ser informado.</li>";

        if ($procedimento->pagamento_tipo_id <= 0)
            $result .= "<li>O campo <b>Forma de Pagamento </b> deve ser informado.</li>";

        if ($procedimento->usuario_id <= 0)
            $result .= "<li>O campo <b>Colaborador </b> deve ser informado.</li>";

        if ($procedimento->status <= 0)
            $result .= "<li>O campo <b>Status </b> deve ser informado.</li>";

        //$result .= $this->_validateProcedimentoDetalhe($procedimento);

        return $result;
    }

    private function _validateProcedimentoDetalhe(Procedimento $procedimento){



        return $result;
    }

    private function _getFileName(Procedimento $procedimento) {
        chdir(APPLICATION_UPLOAD_PATH . '/p');
        return getcwd() . '/' . AppUtil::getFileName($procedimento);
    }

    private function _saveImage(Procedimento $procedimento, $form, $data) {
        if ($form->isValid($data)) {
            $form->getValues();

            $filename = $form->file->getFileName(null, false);
            $filename = getcwd() . '/' . $filename;

            $filename_new = $this->_getFileName($procedimento);

            copy($filename, $filename_new);
            unlink($filename);
        }
    }

    private function _deleteImage(Procedimento $procedimento) {
        $filename = $this->_getFileName($procedimento);
        unlink($filename);
    }

    private function _sendMail(Procedimento $procedimento) {

        $app_email = Zend_Registry::getInstance()->get('email');
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('procedimento', $procedimento);
        $html->assign('empresa', $usuario->Empresa);
        $html->assign('remetente', $app_email->mail->de);


        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = $usuario->email;

        $subject = "Seja Bem Vinda ao Espaço ELA";
        $body = $html->render('lembrar_senha.phtml');

        EmailUtil::send($from, $to, $subject, $body);
    }

}
