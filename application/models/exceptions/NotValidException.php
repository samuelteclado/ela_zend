<?php

class NotValidException extends Exception {

    private $_exceptionsValidation = array();

    public function  __construct($exceptionsValidation) {
        $this->_exceptionsValidation = $exceptionsValidation;
    }

    public function getExceptionsToJson() {
        $ret = array();
        foreach ($this->_exceptionsValidation as $exe) {
            $ret []= $exe->toJSon();
        }
        return "[".implode(',', $ret)."]";
    }
    
}

