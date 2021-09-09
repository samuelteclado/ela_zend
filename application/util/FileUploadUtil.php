<?php
/**
 *
 * @author mateus
 */
class FileUploadUtil {

    /**
     *
     * @param Zend_Form_Element_File $file
     */
    public static function prepareFileToUpdalod ($file) {
        if(!$file) {
            return '';
        }
        $dsimagemFileName = $file->getFileName();
        $file->receive();
        if($file->isUploaded()) { // Se foi enviada a imagem, pego as infos necessarias para mexer posteriormente
            $dsimagemExtension = pathinfo($dsimagemFileName);
            $dsimagemFileNameTmp = explode('/', $file->getFileName());
            $dsimagemExtension = $dsimagemExtension['extension'];
            $dsimagemFileNameTmp = $dsimagemFileNameTmp[count($dsimagemFileNameTmp)-1];
            $fileName = $dsimagemFileNameTmp;
        }
        $fileName = str_replace('\\', '/', $fileName);
        return $fileName;
    }

    /**
     *
     * @param Zend_Form_Element_File $file
     */
    public  static function renameFile($dsimagemFileName, $prefix, $directori) {
        if(!is_array($dsimagemFileName) && $dsimagemFileName) {
            $dsimagemInfo = pathinfo($dsimagemFileName);
            $dsimagemExtension = $dsimagemInfo['extension'];
            $toUpdate = array();
            $newname = APPLICATION_FILES_PATH.DIRECTORY_SEPARATOR.$directori.DIRECTORY_SEPARATOR.$prefix.$dsimagemInfo['filename'].'.'.$dsimagemExtension;
            $newname = str_replace(' ','',$newname);
            rename($dsimagemFileName, $newname);
            return  str_replace(' ','',$directori."/".$prefix.$dsimagemInfo['filename'].'.'.$dsimagemExtension);
        }else {
            return null;
        }
    }
} 