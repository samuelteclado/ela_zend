<?php

/*
 * jQuery File Upload Plugin PHP Example 5.7
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);

require('upload.class.php');

$upload_handler = new UploadHandler();

$f_id = md5($_REQUEST['id']);
$diretorio = '/f/' . $f_id;
$_numero_max_arquivos = 5;

$options = array(
    'script_url' => $upload_handler->getFullUrl() . '/',
    'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']) . $diretorio . '/',
    'upload_url' => $upload_handler->getFullUrl() . $diretorio . '/',
    'max_number_of_files' => $_numero_max_arquivos,
    'image_versions' => array(
        'thumbnail' => array(
            'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']) . $diretorio . '_thumb/',
            'upload_url' => $upload_handler->getFullUrl() . $diretorio . '_thumb/',
            'max_width' => 80,
            'max_height' => 80
        )
    )
);

$upload_handler->setOptions($options);

header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Content-Disposition: inline; filename="files.json"');
header('X-Content-Type-Options: nosniff');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'OPTIONS':
        break;
    case 'HEAD':
    case 'GET':
        $upload_handler->get();
        break;
    case 'POST':
        if (isset($_REQUEST['_method']) && $_REQUEST['_method'] === 'DELETE') {
            $upload_handler->delete();
        } else {
            if (!file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . $diretorio . '/'))
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . $diretorio . '/');

            if (!file_exists(dirname($_SERVER['SCRIPT_FILENAME']) . $diretorio . '_thumb/'))
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . $diretorio . '_thumb/');

            $upload_handler->post();
        }
        break;
    case 'DELETE':
        $upload_handler->delete();
        break;
    default:
        header('HTTP/1.1 405 Method Not Allowed');
}