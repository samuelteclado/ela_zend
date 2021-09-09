<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    public function _initTranslate() {
        $translate = new Zend_Translate(
                'gettext', APPLICATION_PATH . '/include/pt_BR.mo'
        );

        Zend_Validate_Abstract::setDefaultTranslator($translate);
    }

    public function _initSession() {
        //SessionUtil::getExpirationSession();
    }

    /**
     * _initHelpers
     *
     * @desc Sets alternative ways to helpers
     */
    protected function _initHelpers() {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->initView();

        // add zend view helper path
        $viewRenderer->view->addHelperPath('ZF/View/Helper/');

        // add zend action helper path
        Zend_Controller_Action_HelperBroker::addPath('ZF/Controller/Helper/');
    }

}
