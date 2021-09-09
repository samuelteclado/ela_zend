<?php
/**
 * Description of FormGeneric
 *
 * @author mateus
 */
class FormGeneric extends Zend_Form {
    
    public function __construct($options = null) {
        parent::__construct($options);
        $this->setFieldDafault();
    }

    public function setAction($action,$include_base = true) {
        if($include_base) {
            return  parent::setAction(ViewGeneric::baseUrl().$action);
        }else {
            return  parent::setAction($action);
        }
    }

    public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
    }

    public function setFieldDafault() {
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setValue('Enviar');
        $submit->setLabel('Salvar');
        $this->addElement($submit);
        $submit->setIgnore(true);
        $elements = $this->getElements();
        foreach($elements as $element) {
            $element->addDecorator('Label', array('tag' => 'td', 'class' => 'label-elemet'));
            $element->addDecorator('HtmlTag', array('tag' => 'td', 'class' => 'elemet'));
            $element->addDecorator(array('row'=>'HtmlTag'), array('tag' => 'tr'));
            $element->setAttrib('class', 'admin_form');
        }
        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements', array('tag' => 'td', 'class' => 'zend_form'))
                    ->addDecorator('HtmlTag', array('tag' => 'table', 'class' => 'zend_form'))
                    ->addDecorator('Form');
        }
        $submit->setDecorators(array(
                'ViewHelper',
                array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'form_submit')),
                array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
                array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
        ));
        $submit->setAttrib('class', 'button-element');
    }
}

