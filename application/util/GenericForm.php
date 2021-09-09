<?php

/**
 * Description of FormGeneric
 *
 * @author Paulo
 */
class GenericForm extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
    }

    public function setAction($action, $include_base = true) {
        if ($include_base) {
            return parent::setAction(ViewGeneric::baseUrl() . $action);
        } else {
            return parent::setAction($action);
        }
    }

    public function loadDefaultDecorators() {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return;
        }
    }

    public function configTextBoxes() {
        $elements = $this->getElements();
        foreach ($elements as $element) {
            $element->addDecorator('Label', array('tag' => 'div', 'class' => 'label-div'));
            $element->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'text-div'));
            $element->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'textbox-div'));
            $element->setAttrib('class', 'textbox-element');
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FormElements', array('tag' => 'div', 'class' => 'zend_form'));
            $this->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'form-element'));
            $this->addDecorator('Form');
        }
    }

    public function configActionButton($label) {
        $submit = new Zend_Form_Element_Submit('Submit');
        $submit->setLabel($label);
        $submit->setValue('Submit');
        $submit->setAttrib('class', 'button-element');
        $submit->setIgnore(true);
        $this->addElement($submit);
        $submit->setDecorators(array(
                'ViewHelper',
                array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'action-div')),
        ));
    }
}