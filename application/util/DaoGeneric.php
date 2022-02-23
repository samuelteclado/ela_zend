<?php

abstract class DaoGeneric extends Doctrine_Record {

    private $auditing = TRUE;

    public function save(Doctrine_Connection $conn = null) {
        $metodo = (current($this->identifier()) > 0) ? $metodo = 'update' : 'create';

        parent::save($conn);
        $this->_addAuditing($metodo);
    }

    public function delete(Doctrine_Connection $conn = null) {
        parent::delete($conn);

        $this->_addAuditing('delete');
    }

    public function setAuditing($auditing) {
        $this->auditing = $auditing;
    }

    public function isValid() {
        return true;
    }

    private function _getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    private function _addAuditing($metodo) {
        if (!$this->auditing)
            return;

        $obj = $this->toArray(FALSE);

        $auditoria = new Auditoria();
        $auditoria->setAuditing(FALSE);
        $auditoria->objeto_id = current($this->identifier());
        $auditoria->objeto_classe = get_class($this);
        $auditoria->objeto = json_encode($obj);
        $auditoria->metodo = $metodo;
        $auditoria->date = date('Y/m/d h:i:s');
        $auditoria->ip = $this->_getUserIpAddr();
        $auditoria->browser = AppUtil::getBrowser();

        $auditoria->empresa_id = Zend_Auth::getInstance()->getIdentity()->empresa_id;
        $auditoria->usuario_id = Zend_Auth::getInstance()->getIdentity()->id;

        $auditoria->save();
    }

}
