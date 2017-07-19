<?php
/*
	框架自动加载类
*/
class core_autoload{

	//自动加载函数
	public static function autoload($className){
		if(preg_match('/(lib|core)/', strtolower($className))){
			$path = ROOT.'/'.strtolower(str_replace('_', '/', $className)).'.php';
		}else if(strpos(strtolower($className),'controller') !== false){//控制器
			$path = CONT_PATH.'/'.$className.'.php';
		}else if(strpos(strtolower($className),'model') !== false){//模型文件
			if(strpos(strtolower($className),'common') !== false){//公用的模型文件
				$path = MODEL.'/common/'.$className.'.php';
			}else{
				$path = MODEL.'/'.$className.'.php';
			}
		}
		// include_once($path);
		if(is_file($path)){
			include_once($path);
		}else{
			show_404();
		}
	}
}