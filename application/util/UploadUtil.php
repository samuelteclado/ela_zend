<?php

/**
 * Description of Application
 *
 * @author Paulo
 */
class UploadUtil {

    const UPLOAD_PATH_EMPRESA = "empresa";
    const UPLOAD_PATH_USUARIO = "usuario";
    const UPLOAD_PATH_CARTEIRA = "carteira";
    const UPLOAD_PATH_PROFESSOR = "professor";
    const UPLOAD_PATH_BANNER = "banner";
    const UPLOAD_PATH_NOTICIA = "noticia";
    const UPLOAD_PATH_MATERIAL = "material";
    const UPLOAD_PATH_AVALIACAO = "avaliacao";
    const UPLOAD_PATH_TCC = "tcc";
    const UPLOAD_PATH_TCC_HISTORICO = "tcc-historico";
    const UPLOAD_PATH_ESTAGIO = "estagio";
    const UPLOAD_PATH_ESTAGIO_HISTORICO = "estagio-historico";
    const UPLOAD_PATH_QUESTAO = "questao";
    const UPLOAD_PATH_BIBLIOTECA_ITEM_ANEXO = "biblioteca-item-anexo";
    const UPLOAD_PATH_CURSO = "curso";
    const UPLOAD_PATH_ATENDIMENTO = "atendimento";
    const UPLOAD_PATH_RECURSO = "recurso";


    public static function uploadItemAnexo($obj, $file, $path) {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        $files_config = Zend_Registry::getInstance()->get('files');
        $path_temp = APPLICATION_UPLOAD_PATH . $files_config->path->temp;

        chdir($path_temp);
        $dir = getcwd();

        if (move_uploaded_file($file['tmp_name'], $dir . '/' . basename($file['name']))) {
            $filename = getcwd() . '/' . basename($file['name']);

            chdir(APPLICATION_UPLOAD_PATH . '/' . $path);

            $fileArray = explode('.', $file['name']);
            $extensao = array_pop($fileArray);
            $nomeSemExtensao = substr($file['name'], 0, - (strlen($extensao) + 1));

            $nomeArquivo = $nomeSemExtensao.'.'.$extensao;
            $filename_new = getcwd() . '/' . $nomeSemExtensao.'.'.$extensao;

            $cont = 1;

            while (is_file($filename_new)) {
                $nomeArquivo = $nomeSemExtensao.'_'.$cont.'.'.$extensao;
                $filename_new = getcwd() . '/' . $nomeArquivo;
                $cont++;
            }

            copy($filename, $filename_new);

            unlink($filename);
        }

        clearstatcache();

        return $nomeArquivo;
    }



    public static function uploadFile($obj, $file, $path, $imageSuffix = NULL) {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        $files_config = Zend_Registry::getInstance()->get('files');
        $path_temp = APPLICATION_UPLOAD_PATH . $files_config->path->temp;

        chdir($path_temp);
        $dir = getcwd();

        if (move_uploaded_file($file['tmp_name'], $dir . '/' . basename($file['name']))) {
            $filename = getcwd() . '/' . basename($file['name']);
            $filename_new = UploadUtil::_getFileName($obj, $path, $imageSuffix);

            copy($filename, $filename_new);

            unlink($filename);
        }

        clearstatcache();
    }

    public static function uploadFilePng($obj, $file, $path, $imageSuffix = NULL) {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        $files_config = Zend_Registry::getInstance()->get('files');
        $path_temp = APPLICATION_UPLOAD_PATH . $files_config->path->temp;

        chdir($path_temp);
        $dir = getcwd();

        if (move_uploaded_file($file['tmp_name'], $dir . '/' . basename($file['name']))) {
            $filename = getcwd() . '/' . basename($file['name']);

            chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
            $filename_new = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'png');

            copy($filename, $filename_new);

            unlink($filename);
        }

        clearstatcache();
    }

    public static function uploadFilePdf($obj, $file, $path, $imageSuffix = NULL) {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        $files_config = Zend_Registry::getInstance()->get('files');
        $path_temp = APPLICATION_UPLOAD_PATH . $files_config->path->temp;

        chdir($path_temp);
        $dir = getcwd();

        if (move_uploaded_file($file['tmp_name'], $dir . '/' . basename($file['name']))) {
            $filename = getcwd() . '/' . basename($file['name']);

            chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
            $filename_new = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'pdf');

            copy($filename, $filename_new);

            unlink($filename);
        }

        clearstatcache();
    }

    public static function uploadFileMp3($obj, $file, $path, $imageSuffix = NULL) {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        $files_config = Zend_Registry::getInstance()->get('files');
        $path_temp = APPLICATION_UPLOAD_PATH . $files_config->path->temp;

        chdir($path_temp);
        $dir = getcwd();

        if (move_uploaded_file($file['tmp_name'], $dir . '/' . basename($file['name']))) {
            $filename = getcwd() . '/' . basename($file['name']);

            chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
            $filename_new = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'mp3');

            copy($filename, $filename_new);

            unlink($filename);
        }

        clearstatcache();
    }

    public static function uploadFileDoc($obj, $file, $path, $imageSuffix = NULL) {
        if (!is_uploaded_file($file['tmp_name']))
            return false;

        $files_config = Zend_Registry::getInstance()->get('files');
        $path_temp = APPLICATION_UPLOAD_PATH . $files_config->path->temp;

        chdir($path_temp);
        $dir = getcwd();

        if (move_uploaded_file($file['tmp_name'], $dir . '/' . basename($file['name']))) {
            $filename = getcwd() . '/' . basename($file['name']);

            chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
            $filename_new = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'doc');

            copy($filename, $filename_new);

            unlink($filename);
        }

        clearstatcache();
    }

    public static function removeFile($obj, $path, $imageSuffix = NULL) {
        $filename = UploadUtil::_getFileName($obj, $path, $imageSuffix);

        $result = unlink($filename);

        clearstatcache();

        return $result;
    }

    public static function removeFilePdf($obj, $path, $imageSuffix = NULL) {
        chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
        $filename = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'pdf');

        $result = unlink($filename);

        clearstatcache();

        return $result;
    }

    public static function removeFileMp3($obj, $path, $imageSuffix = NULL) {
        chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
        $filename = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'mp3');

        $result = unlink($filename);

        clearstatcache();

        return $result;
    }

    public static function removeFileDoc($obj, $path, $imageSuffix = NULL) {
        chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
        $filename = getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix, 'doc');

        $result = unlink($filename);

        clearstatcache();

        return $result;
    }

    private static function _getFileName($obj, $path, $imageSuffix) {
        chdir(APPLICATION_UPLOAD_PATH . '/' . $path);
        return getcwd() . '/' . AppUtil::getFileName($obj, $imageSuffix);
    }

}