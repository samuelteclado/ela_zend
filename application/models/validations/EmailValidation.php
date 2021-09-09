<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmailValidation
 *
 * @author mateus
 */
class EmailValidation implements Validable {
    private $value;
    private $fieldName;
    public function  __construct($obj,$fieldName) {
        $this->value= $obj->{$fieldName};
        $this->fieldName = $fieldName;
    }
    public function validate() {
        $zv = new Zend_Validate_EmailAddress();
        if(!$zv->isValid($this->value)){
            throw new EmailInvalidException($this->fieldName,'Seu e-mail não é válido.');
        }
    }
}