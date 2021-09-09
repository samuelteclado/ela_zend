<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidationException
 *
 * @author mateus
 */
class ValidationException extends Exception {


    public function toJSon(){
        
        return "{'message':'{$this->getMessage()}' }";
    }
}
