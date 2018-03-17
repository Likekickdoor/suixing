<?php
    namespace lib;
    //加载文件库
    require_once(CORE.'/config/require_list.php');
    require_once(LIB.'/start.php');
    if(!isset($config)){
        die('找不到核心配置文件');
    }
    //开始
    start::run($config);
    C(start::$con,start::$med);
?>