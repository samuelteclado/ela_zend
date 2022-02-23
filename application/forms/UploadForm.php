<?php

class UploadForm extends Zend_Form {

    public function __construct($options = null) {
        parent::__construct($options);
        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');

        if ( is_array($options) ) {
            $path = $options['path'];
            $name = ($options['name'] == "") ? 'file' : $options['name'];
        } else {
            $name = 'file';
            $files_config = Zend_Registry::getInstance()->get('files');
            $path = APPLICATION_UPLOAD_PATH . $files_config->path->temp;
        }
        
        chdir($path);
        $dir = getcwd();
        
        $file = new Zend_Form_Element_File($name);
        $file->setDestination($dir)
                ->addValidator('Count', false, 1)
                ->addValidator('Extension', false, 'bmp,jpg,jpeg,png,gif,ret')
                ->setRequired(true);

        $file->removeDecorator('Errors');
        $file->removeDecorator('HtmlTag');
        $file->removeDecorator('Label');

        $this->addElement($file);
    }

}