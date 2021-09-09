<?php

class SessionUtil {

    public static function getEscolaSession() {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $escolaRepository = new EscolaRepository();
        $escola = $escolaRepository->getById($usuario->escola_id);
        return $escola->id;
    }

    public static function getEmpresaSession() {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $empresaRepository = new EmpresaRepository();
        $empresa = $empresaRepository->getById($usuario->empresa_id);
        return $empresa->id;
    }


    public static function getIdUser() {
        return Zend_Auth::getInstance()->getIdentity()->id;
    }

    public static function getExpirationSession() {
        $tempo = 60000;
        if ($tempo <= 0)
            $tempo = 7200;
        $session = new Zend_Session_Namespace(Zend_Auth::getInstance()->getStorage()->getNamespace());
        $session->setExpirationSeconds($tempo);
    }

    private function _getTimeEscolaSession() {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $escolaRepository = new EscolaRepository();
        $escola = $escolaRepository->getById($usuario->escola_id);
        return $escola->EscolaConfiguracao[0]->expira_sessao * 600;
    }

}
