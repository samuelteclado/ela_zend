<?php

class Site_IndexController extends Zend_Controller_Action {

    public function indexAction() {
        $this->_redirect($this->view->baseUrl() . '/institucional');
    }
}
    