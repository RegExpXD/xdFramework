<?php
//模型对象
function M($modelName){
	$targetName = $modelName;
	if(strpos(strtolower($modelName),'_model') === false){
		$targetName = $modelName().'_model';
	}
	return new $targetName();
}
//控制器对象
function C($controllerName){
	return new $controllerName();
}
//视图对象
function V($viewName){
	return new $viewName();
}
//操作session
function S($sessionName = null){
	if(is_null($sessionName)){
		return $_SESSION;
	}
	return $_SESSION[$sessionName]?$_SESSION[$sessionName]:false;
}
//404页面
function show_404(){
	exit('page not found!');
}