<?php
    define('FRAME',__DIR__);
    define('APP',FRAME.'/app');
    define('LIB',FRAME.'/lib');
    define('LOG',FRAME.'/log');
    define('CORE',LIB.'/core');
    define('DRIVE',CORE.'/drive');
    define('CONFIG',CORE.'/config');
    define('DEBUG',false);

    require_once(FRAME.'/vendor/autoload.php');

    if(DEBUG){
        $whoops = new \Whoops\Run;
        $option = new \Whoops\Handler\PrettyPageHandler();
        // $option->setPageTitle('出错了!');
        $whoops->pushHandler($option);
        $whoops->register();
        ini_set('display_error','On');
    }else{
        ini_set('display_error','Off');
    }

    require_once(LIB.'/admin.php');
?>
