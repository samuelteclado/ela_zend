<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CheckNull
 *
 * @author mateus
 */
class CheckNull implements Validable {
    private $value;
    private $fieldName;
    private $title;
    public function  __construct($obj,$fieldName, $title) {
        $this->value= $obj->{$fieldName};
        $this->fieldName = $fieldName;
        $this->title = $title;
    }
    public function validate() {
        
        if(empty ($this->value)){
            throw new IsNullException($this->fieldName,$this->title.' não pode ficar em branco.');
        }
    }
}
