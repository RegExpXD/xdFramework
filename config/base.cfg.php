<?php
//框架根目录
define('ROOT',dirname(__DIR__));
//模型目录
define('MODEL',ROOT.'/model');
//模板文件外层目录
define('TEMPLATE',ROOT.'/template');
//编译后模板文件外层目录
define('TEMPLATE_C',ROOT.'/template_c');

//默认index模块
!defined('SITE_CONT') && define('SITE_CONT','index');

//设置自动加载路径
set_include_path('.'.PATH_SEPARATOR.ROOT.'/'.SITE_CONT.PATH_SEPARATOR.MODEL.PATH_SEPARATOR.TEMPLATE.PATH_SEPARATOR.TEMPLATE_C.PATH_SEPARATOR.get_include_path());

//是否为调试模式
define('DEBUG',true);

//默认数据库
define('DEFAULT_DATABASE','xyb');