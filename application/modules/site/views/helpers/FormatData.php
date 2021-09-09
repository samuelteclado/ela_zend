<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormatData
 *
 * @author mateus
 */
class Zend_View_Helper_FormatData extends Zend_View_Helper_Abstract {
    public function formatData($data,$type = DaoGeneric::DATA_US){
        return DaoGeneric::formatData($data, $type);
    }
}
?>
