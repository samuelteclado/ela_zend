<?php

class PageUtil extends Zend_View {

    private static $_title = 'Espaço ELA';

    public static function getTitle($page = null, $titulo = null) {
        if (isset($titulo))
            return $titulo . ' :: Espaço ELA';

        if (isset($page))
            return $page . ' :: Espaço ELA';

        return PageUtil::$_title;
    }

    public static function getLogo() {
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $empresaRepository = new EmpresaRepository();
        $empresa = $empresaRepository->getById($usuario->empresa_id);

        if ($empresa->id > 0) {
            return AppUtil::getFileView($empresa, 'e', '60');
        } else {
            return '/content/img/logo-ela.png';
        }
    }

}
