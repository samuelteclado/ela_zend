<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AclVerify
 *
 * @author mateus
 */
class AclVerify extends Zend_Controller_Plugin_Abstract {
    
    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $this->populateAclRules();
        try {
            $acessrule = $this->acl->isAllowed($request->getControllerName().'/'.$request->getActionName());
            if(!$acessrule) {
               $request->setControllerName('index');
               $request->setActionName('acesso');
            }
        }catch(Exception $e ) {}
    }
    /**
     *
     * @var AclUtil
     */
    private  $acl = null;
    public function populateAclRules() {
        $this->acl = AclUtil::getInstance();

    }
}