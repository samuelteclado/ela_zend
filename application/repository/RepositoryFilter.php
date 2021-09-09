<?php

class RepositoryFilter {

    private $_filters = array();
    private $_filtersGeneric = array();
    private $_filtersJoin = array();
    private $_filtersLeftJoin = array();
    private $_params = array();

    public function __construct($params = null) {
        $this->_params = $params;
    }

    public function addFilter($field, $value) {
        $this->_filters[] = array($field, $value);
    }
    
    public function addGenericFilter($filter) {
        $this->_filtersGeneric[] = $filter;
    }
    
    public function addNullFilter($field) {
      $this->addGenericFilter($field . ' IS NULL');
    }
    
    public function addNotNullFilter($field) {
      $this->addGenericFilter($field . ' IS NOT NULL');
    }

    public function addJoinFilter($table, $field = null, $value = 0 ) {
        $this->_filtersJoin[] = $table;
        if ($value > 0) {
            $this->addFilter($field . ' = ?', $value);
        }
    }
    
    public function addLeftJoinFilter($table, $field, $value) {
        $this->_filtersLeftJoin[] = $table;
        if ($value > 0) {
            $this->addFilter($field . ' = ?', $value);
        }
    }

    public function addTextFilter($field, $value) {
        if (strlen($value) > 0)
            $this->addFilter($field . ' LIKE ?', '%' . $value . '%');
    }
    
     public function addDateFilter($field, $value) {
        if (strlen($value) > 0)
            $this->addFilter($field . ' = ?', AppUtil::convertStringToDate($value));
    }

    public function addSelectFilter($field, $value) {
        if ($value > 0)
            $this->addFilter($field . ' = ?', $value);
    }

    public function getFilters() {
        return $this->_filters;
    }
    
    public function getFiltersGeneric() {
        return $this->_filtersGeneric;
    }

    public function getFiltersJoin() {
        return $this->_filtersJoin;
    }
    
    public function getFiltersLeftJoin() {
        return $this->_filtersLeftJoin;
    }

    public function isEmpty() {
        return is_null($this->_filters) && count($this->_filters) <= 0;
    }
    
    public function getParamByField($field) {
        return $this->_params[$field];
    }
    
    public function getParamString($p_remove = null) {
        $propert_remove = array("controller", "action", "module", "page");
        
        foreach ($propert_remove as $remove) {
            unset($this->_params[$remove]);
        }
        
         foreach ($p_remove as $remove) {
            unset($this->_params[$remove]);
        }
        
        $result = '&';
        
        foreach ($this->_params as $key => $value) {
            $result .= $key . '=' . $value . '&';
        }
        
        return substr($result, 0, strlen($result) - 1);
    }

}
