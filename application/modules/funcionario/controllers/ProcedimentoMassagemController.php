<?php

class Funcionario_ProcedimentoMassagemController extends Zend_Controller_Action {

    private $_procedimentoRepository;
    private $_clienteRepository;
    private $_pagamentoTipoRepository;
    private $_procedimentoTipoRepository;
    private $_contaBancariaRepository;
    private $_lancamentoRepository;
    private $_usuarioRepository;
    private $_planodeContasRepository;
    private $_empresa_id;

    public function init() {
        $this->_procedimentoRepository = new ProcedimentoRepository();
        $this->_pagamentoTipoRepository = new PagamentoTipoRepository();
        $this->_clienteRepository = new ClienteRepository();
        $this->_contaBancariaRepository = new ContaBancariaRepository();
        $this->_procedimentoTipoRepository = new ProcedimentoTipoRepository();
        $this->_usuarioRepository = new UsuarioRepository();
        $this->_lancamentoRepository = new LancamentoRepository();
        $this->_planodeContasRepository = new PlanodeContasRepository();
        $this->_empresa_id = SessionUtil::getEmpresaSession();
    }

    public function indexAction() {
        $params = $this->getRequest()->getParams();

        $params['ano'] = ($params['ano']) ? $params['ano'] : date('Y');
        $params['mes'] = ($params['mes']) ? $params['mes'] : date('m');
        $ano = $params['ano'];
        $mes = $params['mes'];
        $mais_31_dias = AppUtil::getAdicionarDia(31);

        $ultimo_dia = date("d", mktime(0, 0, 0, ($mes + 1), 0, $ano));

        $filter = new RepositoryFilter($params);
        //$filter->addGenericFilter('(data BETWEEN "' . date("$ano-$mes-01") . '" AND "' . date("$ano-$mes-$ultimo_dia") .'")');
        $filter->addGenericFilter('(data BETWEEN "' . date('Y-m-d') . '" AND "' . date("$mais_31_dias") .'")');

        $filter->addSelectFilter('cliente_id', $params['cliente']);
        $filter->addFilter('status != ?', Procedimento::EXCLUIDO);
        $filter->addFilter('tipo = ?', Procedimento::MASSAGEM);
        $filter->addSelectFilter('status', $params['status']);
        $filter->addDateFilter('data', $params['procedimento_data']);
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
        $sortParam2 = ($params["sort"]) ? $params["sort"] : 'horario';


        $orderParam = ($params["order"]) ? $params["order"] : 'DESC';
        $orderby = new RepositoryOrder($params);
        $orderby->addOrder($sortParam, ($orderParam == 'ASC') ? 'DESC' : 'ASC');
        $orderby->addOrder($sortParam2, ($orderParam == 'ASC') ? 'DESC' : 'ASC');

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

            $validate = $this->_validate($procedimento, $data);
            if (!isset($validate) || $validate == "") {

                $procedimento->save();
                $this->_saveImage($procedimento, $form, $data);


                $this->_helper->FlashMessenger('Procedimento cadastrado com sucesso.');
                $this->_redirect($this->view->baseUrl() . '/funcionario/procedimento-massagem/');
            } else {
                $this->_helper->FlashMessenger(array('warning' => $validate));
                $this->view->form = $form;
                $this->view->procedimento = $procedimento;
                $this->view->clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);
                $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id, ProcedimentoTipo::MASSAGEM);
                $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByMassagem($this->_empresa_id);

            }
        } else {
            $this->view->clientes = $this->_clienteRepository->getListByEmpresa($this->_empresa_id);
            $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id, ProcedimentoTipo::MASSAGEM);
            $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
            $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByMassagem($this->_empresa_id);
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

            $this->_helper->FlashMessenger(array('info' => 'Foto removida com sucesso.'));
            $this->view->form = $form;
            $this->view->procedimento = $procedimento;
        } else {
            $id = $this->getRequest()->getParam('id');
            $procedimento = $this->_procedimentoRepository->getById($id);

            if ($this->getRequest()->isPost()) {

                $data = $this->getRequest()->getPost();
                $this->_setData($procedimento, $data);

                $validate .= $this->_validate($procedimento,$data);
                if (!isset($validate) || $validate == "") {
                    if($procedimento->status == Procedimento::CANCELADO)
                        $this->_setTratarLancamento($procedimento);

                    $procedimento->save();
                    $this->_saveImage($procedimento, $form, $data);

                    $this->_helper->FlashMessenger('Procedimento alterado com sucesso.');
                    $this->_redirect($this->view->baseUrl() . '/funcionario/procedimento-massagem/');
                } else {
                    $this->_helper->FlashMessenger(array('warning' => $validate));
                    $this->view->form = $form;
                    $this->view->procedimento = $procedimento;
                    $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
                    $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id, ProcedimentoTipo::MASSAGEM);
                    $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                    $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByMassagem($this->_empresa_id);
                }
            } else {
                $this->view->form = $form;
                $this->view->procedimento = $procedimento;
                $this->view->clientes = $this->_clienteRepository->getListAll($this->_empresa_id);
                $this->view->procedimento_tipos = $this->_procedimentoTipoRepository->getListByEmpresa($this->_empresa_id, ProcedimentoTipo::MASSAGEM);
                $this->view->pagamento_tipos = $this->_pagamentoTipoRepository->getListByEmpresa($this->_empresa_id);
                $this->view->colaboradores = $this->_usuarioRepository->getListColaboradoresByMassagem($this->_empresa_id);
            }
        }
    }

    public function deletarAction() {
        $id = $this->getRequest()->getParam('id');

        $procedimento = $this->_procedimentoRepository->getById($id, $this->_empresa_id);
        if ($procedimento != FALSE) {
            $procedimento->Lancamento[0]->status = Lancamento::EXCLUIDO;
            $procedimento->status = Procedimento::EXCLUIDO;
            $procedimento->save();

            $this->_helper->FlashMessenger('Procedimento removido com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está tentando excluir um procedimento que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/funcionario/procedimento-massagem/');
    }

    public function confirmarAction() {
        $id = $this->getRequest()->getParam('id');

        $procedimento = $this->_procedimentoRepository->getById($id, $this->_empresa_id);

        if ($procedimento != FALSE) {
            $procedimento->data_confirmacao = date('Y-m-d');
            $procedimento->save();

            $this->_helper->FlashMessenger('Procedimento confirmado com sucesso.');
        } else {
            $this->_helper->FlashMessenger(array('warning' => 'Atenção você está confirmar um procedimento que não existe.'));
        }
        $this->_redirect($this->view->baseUrl() . '/funcionario/relatorio/clientes-confirmar');
    }

    private function _setData(Procedimento $procedimento, $data) {
        $procedimento->data = AppUtil::convertStringToDate($data['data']);
        $procedimento->horario = $data['horario'];
        $procedimento->status = $data['status'];
        $procedimento->valor = AppUtil::convertStringToFloat($data['valor']);
        $procedimento->observacao = $data['observacao'];
        $procedimento->data_confirmacao = AppUtil::convertStringToDate($data['data_confirmacao']);
        $procedimento->cliente_id = $data['cliente'];
        $procedimento->tipo = Procedimento::MASSAGEM;
        $procedimento->pagamento_tipo_id = NULL;
        if($data['pagamento'] != 0)
        $procedimento->pagamento_tipo_id = $data['pagamento'];
        $procedimento->procedimento_tipo_id = NULL;
        if($data['procedimento'] != 0)
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

        //if($procedimento->status != Procedimento::REALIZADO) {
            if ($procedimento->Lancamento[0]->id <= 0) {
                $procedimento_lancamento = new Lancamento();
                $this->_setDataLancamento($procedimento_lancamento, $data);
                $procedimento->Lancamento[0] = $procedimento_lancamento;
            } else {
                $procedimento_lancamento = $procedimento->Lancamento[0];
                $this->_setDataLancamento($procedimento_lancamento, $data);
            }
        //}

    }

    private function _setTratarLancamento($procedimento){
        $lancamentos = $this->_lancamentoRepository->getLancamentosByProcedimento($this->_empresa_id,$procedimento->id);
        foreach ($lancamentos as $lancamento_item){
            $lancamento_item->status = Lancamento::EXCLUIDO;
        }

    }


    private function _setDataLancamento(Lancamento $procedimento_lancamento, $data){


        $cliente = $this->_clienteRepository->getById($data['cliente']);
        $valor = AppUtil::convertStringToFloat($data['valor']);
        $procedimento_lancamento->descricao = $cliente->getFullNome();

        if($data['procedimento'] != 0){
            $procedimentoTipo = $this->_procedimentoTipoRepository->getById($data['procedimento']);
            $plano_de_contas = $this->_planodeContasRepository->getByDescricao($this->_empresa_id,$procedimentoTipo->getCategoria());
            $procedimento_lancamento->plano_de_contas_id = $plano_de_contas->id;
            $procedimento_lancamento->descricao = $procedimentoTipo->getCategoria()." - ".$cliente->getFullNome();
            $procedimento_lancamento->conta_bancaria_id = $this->_contaBancariaRepository->getFistConta($this->_empresa_id);
        }
        if($data['pagamento'] != 0){
            $pagamentoTipo = $this->_pagamentoTipoRepository->getById($data['pagamento']);
            $procedimento_lancamento->pagamento_valor = $valor - ($valor * ($pagamentoTipo->percentual/100));
            $procedimento_lancamento->pagamento_taxa = $valor * ($pagamentoTipo->percentual/100);
            $procedimento_lancamento->pagamento_percentual = $pagamentoTipo->percentual;
            $procedimento_lancamento->pagamento_tipo_id = $data['pagamento'];
        }
        $procedimento_lancamento->status = Lancamento::ABERTO;
        if($data['status'] == Procedimento::CANCELADO)
            $procedimento_lancamento->status = Lancamento::EXCLUIDO;


        if($data['status'] == Procedimento::REALIZADO){
            $procedimento_lancamento->pagamento_data = AppUtil::convertStringToDate($data['data']);

        }

        $procedimento_lancamento->tipo = Lancamento::RECEITA;
        $procedimento_lancamento->vencimento_data = AppUtil::convertStringToDate($data['data']);
        $procedimento_lancamento->vencimento_valor = $valor;


        $procedimento_lancamento->cliente_id = $data['cliente'];
        $procedimento_lancamento->empresa_id = $this->_empresa_id;


    }

    private function _setDataDetalhe(ProcedimentoDetalhe $procedimento_detalhe, $data) {
        $procedimento_detalhe->tratamento_medico = $data['tratamento_medico'];
        $procedimento_detalhe->fumante = $data['fumante'];
        $procedimento_detalhe->covid = $data['covid'];
        $procedimento_detalhe->covid_detalhes = $data['covid_detalhes'];
        $procedimento_detalhe->alergia = $data['alergia'];
        $procedimento_detalhe->alergia_outros = $data['alergia_outros'];
        $procedimento_detalhe->cirurgia_recente = $data['cirurgia_recente'];
        $procedimento_detalhe->cirurgia_recente_detalhe = $data['cirurgia_recente_detalhe'];
        $procedimento_detalhe->pele = $data['pele'];
        $procedimento_detalhe->pele_detalhes = $data['pele_detalhes'];
        $procedimento_detalhe->roacutan = $data['roacutan'];
        $procedimento_detalhe->roacutan_detalhes = $data['roacutan_detalhes'];
        $procedimento_detalhe->gestante = $data['gestante'];
        $procedimento_detalhe->gestante_detalhes = $data['gestante_detalhes'];
        $procedimento_detalhe->marca_passo = $data['marca_passo'];
        $procedimento_detalhe->disturbio_circulatorio = $data['disturbio_circulatorio'];
        $procedimento_detalhe->alteracao_cardiaca = $data['alteracao_cardiaca'];
        $procedimento_detalhe->disturbio_hormonal = $data['disturbio_hormonal'];
        $procedimento_detalhe->disturbio_renal = $data['disturbio_renal'];
        $procedimento_detalhe->modelo_fios = $data['modelo_fios'];
        $procedimento_detalhe->ortopedico = $data['ortopedico'];
        $procedimento_detalhe->ortopedico_detalhes = $data['ortopedico_detalhes'];
        $procedimento_detalhe->diabetes = $data['diabetes'];
        $procedimento_detalhe->diabetes_detalhes = $data['diabetes_detalhes'];
        $procedimento_detalhe->diabete_controlada = $data['diabete_controlada'];
        $procedimento_detalhe->epiletico = $data['epiletico'];
        $procedimento_detalhe->hipo_hipertensao = $data['hipo_hipertensao'];
        $procedimento_detalhe->tumor_lesao = $data['tumor_lesao'];
        $procedimento_detalhe->tumor_lesao_detalhes = $data['tumor_lesao_detalhes'];
        $procedimento_detalhe->protese_facial = $data['protese_facial'];
        $procedimento_detalhe->protese_facial_detalhes = $data['protese_facial_detalhes'];
        $procedimento_detalhe->acido = $data['acido'];
        $procedimento_detalhe->acido_detalhes = $data['acido_detalhes'];
        $procedimento_detalhe->ciclo_menstrual = $data['ciclo_menstrual'];
        $procedimento_detalhe->ciclo_menstrual_detalhes = $data['ciclo_menstrual_detalhes'];
        $procedimento_detalhe->diu = $data['diu'];
        $procedimento_detalhe->diu_detalhes = $data['diu_detalhes'];
        $procedimento_detalhe->intestinal = $data['intestinal'];
        $procedimento_detalhe->info_adicionais = $data['info_adicionais'];
        $procedimento_detalhe->assinatura = $data['assinatura'];
    }

    private function _validate(Procedimento $procedimento, $data) {
        if ($procedimento->data == "")
            $result .= "<li>O campo <b>Data </b> deve ser informado.</li>";

        if ($procedimento->horario == "")
            $result .= "<li>O campo <b>Horário </b> deve ser informado.</li>";

        if ($procedimento->cliente_id <= 0)
            $result .= "<li>O campo <b>Cliente </b> deve ser informado.</li>";

        if ($procedimento->usuario_id <= 0)
            $result .= "<li>O campo <b>Colaborador </b> deve ser informado.</li>";

        if ($procedimento->status <= 0)
            $result .= "<li>O campo <b>Status </b> deve ser informado.</li>";

        if ($procedimento->status == Procedimento::REALIZADO)
                $result .= $this->_validateProcedimentoRealizado($procedimento, $data);

        return $result;
    }

    private function _validateProcedimentoRealizado($procedimento, $data){
        if ($procedimento->pagamento_tipo_id <= 0)
            $result .= "<li>O campo <b>Forma de Pagamento </b> deve ser informado.</li>";

        if ($procedimento->procedimento_tipo_id <= 0)
            $result .= "<li>O campo <b>Procedimento </b> deve ser informado.</li>";

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


    public function enviarAction() {
        $id = $this->getRequest()->getParam('id');

        $procedimento = $this->_procedimentoRepository->getById($id);

        $cliente_id = $procedimento->cliente_id;

        $cliente_obj = $this->_usuarioRepository->getById($cliente_id);

        $this->_sendMail($cliente_obj, $procedimento);

        $this->_helper->FlashMessenger('Email de aprovação enviado com sucesso.');
        $this->_redirect($this->view->baseUrl() . '/funcionario/procedimento-massagem/');
    }

    private function _sendMail($cliente_obj, $procedimento) {

        $app_email = Zend_Registry::getInstance()->get('email');
        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('cliente', $cliente_obj);
        $html->assign('empresa', $cliente_obj->Empresa);
        $html->assign('remetente', $app_email->mail->de);


        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to = $cliente_obj->email;

        $subject = "Aprovação de Massagem do Espaço ELA";
        $body = $html->render('aprovacao_massagem.phtml');

        EmailUtil::send($from, $to, $subject, $body);
    }
}
