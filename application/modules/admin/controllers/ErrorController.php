<?php

class Admin_ErrorController extends Zend_Controller_Action {

    public function errorAction() {
        $this->_helper->layout->disableLayout();
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }

        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;

        //$this->_sendMail($this->view);
        //$this->_redirect($this->view->baseUrl() . '/admin');
    }

    private function _sendMail($view) {

        $usuario = Zend_Auth::getInstance()->getIdentity();
        $empresaRepository = new EmpresaRepository();
        $empresa = $empresaRepository->getById($usuario->empresa_id);

        $app_email = Zend_Registry::getInstance()->get('email');

        $html = new Zend_View();
        $html->setScriptPath(APPLICATION_PATH . '/layouts/emails/');
        $html->assign('message', $view->message);
        $html->assign('exception', $view->exception);
        $html->assign('request', $view->request);
        $html->assign('data', AppUtil::convertDateTimeToString(AppUtil::getCurrentDate()));
        $html->assign('empresa', $empresa->razao_social);
        $html->assign('usuario', $usuario->nome . " (" . $usuario->email . ")");
        $html->assign('url', $this->view->baseUrl() . $view->request->getRequestUri());
        $body = $html->render('error.phtml');

        $from['name'] = $app_email->mail->from->name;
        $from['email'] = $app_email->mail->from->email;

        $to[] = 'samuelteclado@hotmail.com';

        $subject = "[ESPAÇO ELA] Falha no Sistema";

        EmailUtil::send($from, $to, $subject, $body);
    }

}
