<?php
class AclUtil extends Zend_Acl {
    private static $_instance = null;
    private function  __construct() {
        $this->loadPermitions();
    }
    /**
     *
     * @return AclUtil
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function  loadPermitions() {
        $user = Zend_Auth::getInstance()->getIdentity();
        $xml = new SimpleXMLElement(file_get_contents('/home/sergios/public_html/extranet/application/configs/permicao_xml.xml'));
        foreach($xml as $node) {
            if(isset($note->allow) && isset($note->allow->action)) {
                if(!is_string($note->allow->action)) {
                    foreach($note->allow->action as $action) {
                        $rule = new AcessRole($node->controler.'/'.$action);
                        $this->addRuleByXml($user,$node,$rule,AcessRole::ALLOW);
                    }
                }else {
                    $rule = new AcessRole($node->controler.'/'.$note->allow->action);
                    $this->addRuleByXml($user,$node,$rule,AcessRole::ALLOW);
                }
            }
            if(isset($node->deny) && isset($node->deny->action)) {
                if(!is_string($node->deny->action)) {
                    foreach($node->deny->action as $action) {
                        $rule = new AcessRole($node->controler.'/'.$action);
                        $this->addRuleByXml($user,$node,$rule,AcessRole::DENY);
                    }
                }else{
                    $rule = new AcessRole($node->controler.'/'.$note->deny->action);
                    $this->addRuleByXml($user,$node,$rule,AcessRole::DENY);
                }
            }
        }
    }
    /**
     *
     * @param Usuario $user
     * @param  $node  node aclxml
     */
    public function addRuleByXml($user,$node,$rule,$type) {
        if(isset($user->idadmin) && $user->idadmin == $node->perfil) {
            $this->addRole($rule);
            if($type == AcessRole::ALLOW) {
                $this->allow($rule);
            }else {
                $this->deny($rule);
            }
        }
    }
}