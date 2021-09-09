<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of FieldException
 *
 * @author mateus
 */
class FieldException extends ValidationException {
    private $fieldName;
    public function  __construct($fieldName,$message) {
        parent::__construct($message);
        $this->fieldName = $fieldName;
    }

    public function getFieldName() {
        return $this->fieldName;
    }
    public function toJSon() {
        return "{'message':'{$this->getMessage()}','field':'{$this->fieldName}'}";
    }

}

