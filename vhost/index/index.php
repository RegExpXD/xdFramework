<?php
session_start();
//site控制器名称
define('SITE_CONT','index');

require_once(dirname(__FILE__).'/../../config/base.cfg.php');

require_once(dirname(__FILE__).'/../../core/autoload.php');

require_once(dirname(__FILE__).'/../../core/function.php');

//控制器路径
define('CONT_PATH',ROOT.'/vhost/'.SITE_CONT.'/controller');

//自动加载
spl_autoload_register(array('core_autoload','autoload'));


// echo E_NOTICE;exit;
// echo error_reporting();exit;
//自定义异常错误、处理
if(DEBUG){
	$handlerObj = new core_handler();
	set_error_handler(array($handlerObj,'errorHandler'),error_reporting());
	set_exception_handler(array($handlerObj,'exceptionHandler'));
}
//throw new Exception("Error Processing Request");

//路由分发
core_router::instance()->dispath();


