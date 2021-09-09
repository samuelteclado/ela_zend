<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zend_View_Helper_BaseUrl
 *
 * @author mateus
 */
class Zend_View_Helper_BaseUrl extends Zend_View_Helper_Abstract {
    public function baseUrl(){
        return Zend_Controller_Front::getInstance()->getBaseUrl();
    }
}

