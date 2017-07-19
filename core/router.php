<?php
//路由分发类
class core_router{

	private $controller;
	private $action;
	private static $instance = null;

	//路由单例
	public static function instance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	//路由分发
	public function dispath(){
		$this->getPathInfo();
		$controllerName = $this->controller?$this->controller:'indexController';
		$actionName = $this->action?$this->action:'indexAction';
		try{
			$controllerObj = new $controllerName();
		}catch(Exception $e){
			throw new Exception("Error Processing Request,controllerName is not exits;", 1);
		}
		if(method_exists($controllerObj, $actionName)){
			$controllerObj->$actionName();
		}else{
			throw new Exception("Error Processing Request,actionName is not exits;", 1);
		}
	}
	//根据action
	private function getPathInfo(){
		$requestUri = $_SERVER['REQUEST_URI'];
		$queryString = $_SERVER['QUERY_STRING'];
		$coreControllerInstance = core_controller::instance();
		if(strpos($requestUri, '?') !== false && ($action = $coreControllerInstance->_get('a')) && ($controller = $coreControllerInstance->_get('c'))){
			$this->controller = $controller.'Controller';
			$this->action = $action.'Action';
		}else{
			// explode('/', $requestUri);
		}
	}
}