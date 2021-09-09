<?php

class DbUtil {

    public static function setConnectionDoctrine(Zend_Config_Ini $conf) {
        $dsn = 'mysql:dbname='.$conf->db->params->dbname.';host='.$conf->db->params->host.'';
        $user = $conf->db->params->username;
        $password = $conf->db->params->password;
        $dbh = new PDO($dsn, $user, $password);
        $conn = Doctrine_Manager::connection($dbh);
    }

    public static function generateModels() {
        Doctrine_Core::generateModelsFromDb('../application/models', array('doctrine'),
                array(
                'baseClassName'=>'DaoGeneric',
                'packagesPrefix'        =>  'Package',
                'packagesPath'          =>  '',
                'packagesFolderName'    =>  'packages',
                'suffix'                =>  '.php',
                'generateBaseClasses'   =>  true,
                'generateTableClasses'  =>  false,
                'generateAccessors'     =>  false,
                'baseClassPrefix'       =>  'Dao',
                'baseClassesDirectory'  =>  'dao',)
        );
    }
}

