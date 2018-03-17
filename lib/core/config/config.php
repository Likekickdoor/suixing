<?php
    namespace lib\core\config;
    $config = array(
        'dbconfig' => array('dbtype' => 'mysql','dbhost' => '127.0.0.1','dbuser' => 'root',
                'dbpwd' => '','dbname' => 'project','dbcharset' => 'utf8'),
        'logconfig' => array('IS_START' => 'false','PATH' => FRAME.'/log','DB_NAME' => 'log'),
        'viewconfig' => array('FORMAT' => 'html','PATH' => FRAME.'\html')
    );
    
?>