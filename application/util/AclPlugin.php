<?php

class AclPlugin extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        if ($request->getControllerName() == 'auth' OR $request->getControllerName() == 'error' 
                OR $request->getModuleName() == 'ajax' OR ($request->getModuleName() == 'admin' && $request->getControllerName() == 'index') 
                OR ($request->getModuleName() == 'funcionario' && $request->getControllerName() == 'principal')
                OR ($request->getModuleName() == 'admin' && $request->getControllerName() == 'conta' && ($request->getActionName() == 'lembrarsenha'))
                OR ($request->getModuleName() == 'admin' && $request->getControllerName() == 'usuario' && $request->getActionName() == 'editar')
                OR ($request->getModuleName() == 'site'))
            return;

        $auth = Zend_Auth::getInstance();

        $grupo = $auth->getIdentity()->usuario_grupo_id;
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $usuario_grupo_permissao_repository = new UsuarioGrupoPermissaoRepository();
        $permissao = $usuario_grupo_permissao_repository->getPermissaoByGrupoAndFuncionalidade($grupo, $module, $controller, $action);

        if (!$auth->hasIdentity() OR !$permissao->permitido) {
            $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
            $flashMessenger->addMessage(array('error' => 'Acesso Negado'));
            
            $request->setModuleName('admin')
                    ->setControllerName('index')
                    ->setActionName('index');
        }
    }

}
