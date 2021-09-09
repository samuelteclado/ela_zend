<?php

class RepositoryOrder {

    private $_orders = array();
    private $_params = array();

    public function __construct($params = null) {
        $this->_params = $params;
    }

    public function addOrder($field, $value) {
        $this->_orders[$field] = $value;
    }

    public function getOrders() {
        return $this->_orders;
    }

    public function getOrderByField($field) {
        if(isset($this->_orders[$field]))
            return $this->_orders[$field];
            
        return 'DESC';
    }
    
    public function isActive($field) {
        if(isset($this->_orders[$field]))
            return true;
        
        return false;
    }

    public function isEmpty() {
        return is_null($this->_orders) && count($this->_orders) <= 0;
    }
    
    public function getParamString() {
        $propert_remove = array("controller", "action", "module", "sort", "order");
        
        foreach ($propert_remove as $remove) {
            unset($this->_params[$remove]);
        }
        
        $result = '&';
        
        foreach ($this->_params as $key => $value) {
            $result .= $key . '=' . $value . '&';
        }
        
        return substr($result, 0, strlen($result) - 1);
    }

}
