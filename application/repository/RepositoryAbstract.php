<?php

/**
 * Description of RepositoryAbstract
 *
 * @author mateus
 */
abstract class RepositoryAbstract {

    /**
     *
     * @var DaoGeneric
     */
    protected $obj;
    protected $_id = 'id';
    protected $_empresa_id = 'empresa_id';
    protected $_descriptionField = 'name';
    private $_class;
    private static $_propert_remove = array("controller", "action", "module", "Submit");

    public function __construct() {
        $this->_class = get_class($this->obj);
    }

    public function save(array $data) {
        foreach (self::$_propert_remove as $remove) {
            unset($data[$remove]);
        }
        $r = new ReflectionClass($this->_class);
        $obj = $r->newInstance();
        if (isset($data[$this->_id]) && $data[$this->_id] != '') {
            $obj = $this->getById($data[$this->_id]);
        }
        $obj->setArray($data);
        $obj->save();
        return $obj;
    }

    public function getById($id, $empresa_id = NULL) {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where($this->_id . " = ?", array($id));
        if ($empresa_id != NULL)
            $q->addWhere($this->_empresa_id . " = ?", array($empresa_id));
        return $q->fetchOne();
    }

  

    public function remove($id) {
        $obj = $this->getById($id);
        if ($obj)
            $obj->delete();
    }

    public function getPublished() {
        $q = Doctrine_Query::create()
                ->from(get_class($this->obj))
                ->where('publicado = 1')
                ->orderBy('data DESC');
        return $q->execute();
    }

    public function getListByFilter(RepositoryFilter $repository_filter = null, RepositoryOrder $repository_order = null) {
        $q = Doctrine_Query::create()
                ->from($this->_class . ' p');
        if (!is_null($repository_filter) && !$repository_filter->isEmpty()) {
            foreach ($repository_filter->getFiltersJoin() as $filterJoin) {
                $q->innerJoin($filterJoin);
            }

            foreach ($repository_filter->getFiltersLeftJoin() as $filterLeftJoin) {
                $q->leftJoin($filterLeftJoin);
            }

            foreach ($repository_filter->getFiltersGeneric() as $filterGeneric) {
                $q->addWhere($filterGeneric);
            }


            foreach ($repository_filter->getFilters() as $filter) {
                $q->addWhere($filter[0], $filter[1]);
            }
        }

        if (!is_null($repository_order) && !$repository_order->isEmpty()) {
            foreach ($repository_order->getOrders() as $sort => $order) {
                $q->addOrderBy($sort . ' ' . $order);
            }
        }

        //echo $q->getSqlQuery();

        return $q;
    }

    public function getList($where = null, $params = null) {
        $q = Doctrine_Query::create()
                ->from($this->_class . ' p');
        if ($where) {
            $q->addWhere($where, $params);
        }

        return $q->execute();
    }

    public function getListFromSelect() {
        $list = $this->getList();
        $options = array();
        foreach ($list as $obj) {
            $options[$obj->__get($this->_id)] = $obj->__get($this->_descriptionField);
        }
        return $options;
    }

    public function getCount() {
        $q = Doctrine_Query::create()
                ->from($this->_class . ' p');

        return $q->count();
    }

}
