<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of Auth_Generic_Verify
 *
 * @author mateus
 */
class AuthGenericVerify extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        if(!Zend_Auth::getInstance()->hasIdentity()  && $request->getActionName() != 'login' && $request->getActionName() != 'listxml') {
            $request->setControllerName('index');
            $request->setActionName('index');
        }
    }
    
}