<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of Util_Front_Controller
 *
 * @author mateus
 * @todo Renomear essa classe para nome mais sugestivo
 */
class ViewGeneric extends Zend_View {
    const ALERT = 1;
    const ERRO = 2;
    public static $_language = 'pt_BR';
    private $_i18n = array('pt_BR'=>array(
                            'list'=>'Lista',
                            'index'=>'Lista',
                            'pesquisar'=>'Pesquisar',
                            'listuser'=>'Lista',
                            'add'=>'Adicionar',
                            'default'=>'Home',
                            'edit'=>'Editar',
                            'detail'=>'Detalhes',
                            'vitrinemat'=>'Material',
                            'video'=>'Vídeo',
                            'noticia'=>'Notícias',
                            'usuario'=>'Usuário',
                            'pedidopecas'=>'Pedido Peças',
                            'conferipedido'=>'Conferir Pedido'
            )
    );
    private $messages = array(ViewGeneric::ALERT => array(),ViewGeneric::ERRO => array());
    public function  __construct($config=array()) {
        parent::__construct($config);
    }
    public static  function baseUrl() {
        return Zend_Controller_Front::getInstance()->getBaseUrl();
    }
    public function getModulo() {
        $font = Zend_Controller_Front::getInstance();
        $modulo = ucfirst($font->getRequest()->getParam('module'));
        $modulo = $this->_translate($modulo);
        $controler = ucfirst($font->getRequest()->getParam('controller'));
        $controler = $this->_translate($controler);
        $action = ucfirst($font->getRequest()->getParam('action'));
        $action = $this->_translate($action);
        if(Zend_Auth::getInstance()->getIdentity()->idadmin != '1') {
            $lsist ='listuser';
        }else {
            $lsist ='list';
        }
        return "<a href='{$this->baseUrl()}/index/home'>".$modulo." </a > >
			<a href='{$this->baseUrl()}/{$font->getRequest()->getParam('controller')}/{$lsist}'>{$controler}</a>   > ".$action;
    }
    private function _translate($name) {
        if(isset($this->_i18n[self::$_language][strtolower($name)])) {

            return $this->_i18n[self::$_language][strtolower($name)];
        }
        else {
            return $name;
        }
    }
    public function getImgModulo() {
        $font = Zend_Controller_Front::getInstance();
        $controler = $font->getRequest()->getParam('controller');
        if($controler == 'index' && $font->getRequest()->getParam('action')=='pesquisar')
            return 'tg_resultado.png';
        if($controler == 'vitrinemat' && $font->getRequest()->getParam('action')=='pedidopecas')
            return 'tg_pecas.png';
        return 'tg_'.$controler.'s.png';
    }
    public function getActions() {
        $font = Zend_Controller_Front::getInstance();
        $controler = $font->getRequest()->getParam('controller');
        $array_temp = array(1=>array('IMG'=>'bt_add.png','LINK'=>$controler.'/add','CONTENT'=>'Novo'),2 => array('IMG'=>'bt_list.png','LINK'=>$controler.'/list','CONTENT'=>'Lista'));
        $array_return = array();
        foreach($array_temp as $item) {
            try {
                if(AclUtil::getInstance()->isAllowed($item['LINK'])) {
                    $array_return[]=$item;
                }
            }catch(Exception $e) {
                $array_return[]=$item;
            }
        }
        return $array_return;
    }
    public static function renderActions($item) {
        echo  "<spam class='action'><a href='".self::baseUrl()."/{$item['LINK']}'><img src ='".self::baseUrl()."/images/{$item['IMG']}'/>{$item['CONTENT']}</a></spam> ";
    }
    public function getMessages($type = ViewGeneric::ALERT) {
        return $this->messages[$type];
    }
    public function addMessage($message,$type = ViewGeneric::ALERT) {
        $this->messages[$type][] = $message;
    }
}