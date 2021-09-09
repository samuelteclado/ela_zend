<?php

Class My_Zend_Paginator_Adapter_Doctrine implements Zend_Paginator_Adapter_Interface {

    protected $_query;

    public function __Construct(Doctrine_Query_Abstract $doctrineQuery) {
        $this->_query = $doctrineQuery;
    }

    public function getItems($offset, $itemCountPerPage) {
        $this->_query->offset($offset);
        $this->_query->limit($itemCountPerPage);
        return $this->_query->execute();
    }

    public function count() {
        $query = clone($this->_query);
        $query->select("count(*) as total");
        $query->limit(0);
        $query->offset(0);
        $rs = $query->fetchOne(array(), Doctrine::HYDRATE_ARRAY);
        return $rs["total"];
    }

}
