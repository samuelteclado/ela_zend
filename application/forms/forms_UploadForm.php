<?php

class forms_UploadForm extends Zend_Form
{
    public function __construct($folder,$options = null)
    {
        parent::__construct($options);
        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');
    
       chdir('../www/content/upload/'.$folder);
        
        $dir = getcwd();

        $file = new Zend_Form_Element_File('file');
        $file->setDestination($dir)
             ->setRequired(true)
             ->addValidator('Count',false,1)
            // ->addValidator('Size',false,2048000)  tamanho do arquivo
             ->addValidator('Extension',false,'bmp,jpg,jpeg,png,gif')
             ->removeDecorator('Label');

        $this->addElements(array($file));

    }
}