<?php
    define('FRAME',__DIR__);
    define('APP',FRAME.'/app');
    define('LIB',FRAME.'/lib');
    define('CORE',LIB.'/core');
    define('CONFIG',CORE.'/config');
    //加载第三方库
    require_once(FRAME.'/vendor/autoload.php');
    
    require_once(LIB.'/admin.php');
?>
