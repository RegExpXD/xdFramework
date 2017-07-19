<?php
/*
	控制器基类
*/
class core_controller{
	private static $instance = null;

	public static function instance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	//获取$_REQUEST数据
	public function _getParams($key = null){
		if(is_null($key)){
			return $_REQUEST;
		}
		return $_REQUEST[$key];
	}
	//获取$_POST数据
	public function _post($key = null){
		if(is_null($key)){
			return $_POST;
		}
		return $_POST[$key];
	}
	//获取$_GET数据
	public function _get($key = null){
		if(is_null($key)){
			return $_GET;
		}
		return $_GET[$key];
	}
	//指定模板
	public function _render(){

	}
}