<?php
    namespace lib;
    require_once(CORE.'/config/require_list.php');
    require_once(LIB.'/start.php');
    if(!isset($config)){
        die('找不到核心配置文件');
    }
    start::run($config);
    C(start::$con,start::$med);    
?>