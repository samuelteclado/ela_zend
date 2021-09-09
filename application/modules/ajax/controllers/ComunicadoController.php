<?php

class Ajax_ComunicadoController extends Zend_Controller_Action {

    private $_escolaRepository;
    private $_comunicadoRepository;

    public function init() {
        $this->_comunicadoRepository = new ComunicadoRepository();
        $this->_escolaRepository = new EscolaRepository();
    }

    public function filaAction() {
        $comunicados_fila = $this->_comunicadoRepository->getComunicadoFila('0');

        $comunicado = array();
        $comunicado_list = array();

        foreach ($comunicados_fila as $comunicados_obj):
            $comunicado['id'] = $comunicados_obj->id;
            $comunicado['destinatario'] = $comunicados_obj->email;

            $comunicado_list[] = $comunicado;
        endforeach;

        $this->_helper->json(Zend_Json::encode($comunicado_list));
    }

    public function enviarAction() {

        $id = $this->getRequest()->getParam('id');
        $comunicado_fila = $this->_comunicadoRepository->getItemFila($id);

        $escola = $this->_escolaRepository->getById(SessionUtil::getEscolaSession());

        try {
            $app_email = Zend_Registry::getInstance()->get('email');

            $html = new Zend_View();
            $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
            $html->assign('mensagem', $comunicado_fila->Comunicado->mensagem);
            $html->assign('remetente', $escola->EscolaConfiguracao[0]->email);

            $from['name'] = $escola->nome_fantasia;
            $from['email'] = $escola->EscolaConfiguracao[0]->email;

            $to = $comunicado_fila->email;

            $subject = $comunicado_fila->Comunicado->assunto;
            $body = $html->render('comunicado.phtml');

            EmailUtil::send($from, $to, $subject, $body);
            $comunicado_fila->situacao = ComunicadoDestinatario::ENVIADO;
        } catch (Exception $e) {
            $comunicado_fila->log_erro = $e->getMessage();
            $comunicado_fila->situacao = ComunicadoDestinatario::ERRO;
        }

        $comunicado_fila->data_envio = date("Y-m-d H:i:s");
        $comunicado_fila->save();

        $this->_helper->json(Zend_Json::encode('enviado'));
    }

}
