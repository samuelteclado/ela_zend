<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcessRole
 *
 * @author mateus
 */
class AcessRole implements Zend_Acl_Role_Interface {
    const DENY = 'deny';
    const ALLOW = 'allow';
    protected $_id = null;
    public function  __construct($id) {
        $this->_id = $id;
    }
     /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId(){
        return $this->_id;
    }
}
