<?php
//路由分发类
class core_router{

	private $controller;
	private $action;
	private static $instance = null;
	private $requestUri = null;
    private $pathInfo = null;
    private $scriptUrl = null;
    private $baseUrl = null;

	//路由单例
	public static function instance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	//路由分发
	public function dispath(){
		$pathInfo = $this->getPathInfo();
		$pathInfoList = !empty($pathInfo)?explode('/',$pathInfo):array();
		$controllerName = !empty($_GET['c'])?$_GET['c']:'';
		$actionName = !empty($_GET['a'])?$_GET['a']:'';
		if(!empty($controllerName) && !empty($actionName)){
		    $this->controller = $controllerName;
		    $this->action = $actionName;
        }else if(count($pathInfoList) > 0){
            $this->controller = $pathInfoList[0];
            $this->action = !empty($pathInfoList[1])?$pathInfoList[1]:'index';
        }else{
            $this->action = $this->controller = 'index';
        }
        $controllerClass = $this->controller.'Controller';
        $actionClass = $this->action.'Action';
		try{
			$controllerObj = new $controllerClass();
		}catch(Exception $e){
			throw new Exception("Error Processing Request,controllerName is not exits;", 1);
		}
		if(method_exists($controllerObj, $actionClass)){
			$controllerObj->$actionClass();
		}else{
			throw new Exception("Error Processing Request,actionName is not exits;", 1);
		}
	}
	private function getRequestUri(){
	    if($this->requestUri == null){
            if(isset($_SERVER['HTTP_X_REWRITE_URL'])){//IIS
                $this->requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            }else if(isset($_SERVER['REQUEST_URI'])){
                $this->requestUri = $_SERVER['REQUEST_URI'];
                if(isset($_SERVER['HTTP_HOST'])){
                    if(strpos($this->requestUri,$_SERVER['HTTP_HOST']) !== false){
                        $this->requestUri = preg_replace('/^\w+:\/\/[^\/]+/i','',$this->requestUri);
                    }else{
                        $this->requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i','',$this->requestUri);
                    }
                }
            }else if(isset($_SERVER['ORIG_PATH_INFO'])){//IIS 5.0 CGI
                $this->requestUri = $_SERVER['ORIG_PATH_INFO'];
                if(!empty($_SERVER['QUERY_STRING'])){
                    $this->requestUri .= '?'.$_SERVER['QUERY_STRING'];
                }
            }
        }
        return $this->requestUri;
    }
    public function getScriptUrl() {
        if ($this->scriptUrl === null) {
            $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
            if (basename($_SERVER['SCRIPT_NAME']) === $scriptName)
                $this->scriptUrl = $_SERVER['SCRIPT_NAME'];
            else if (basename($_SERVER['PHP_SELF']) === $scriptName)
                $this->scriptUrl = $_SERVER['PHP_SELF'];
            else if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName)
                $this->scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            else if (($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName)) !== false)
                $this->scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            else if (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0)
                $this->scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
            else
                throw new Exception('CHttpRequest is unable to determine the entry script URL.');
        }
        return $this->scriptUrl;
    }

    public function getBaseUrl($absolute=false) {
        if ($this->baseUrl === null)
            $this->baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        return $absolute ? $this->getHostInfo() . $this->baseUrl : $this->baseUrl;
    }
    public function getHostInfo(){
	    $http = 'http';
	    if(isset($_SERVER['HTTP_HOST'])){
            return $http.'://'.$_SERVER['HTTP_HOST'];
        }else if(isset($_SERVER['SERVER_NAME'])){
            return $http.'://'.$_SERVER['SERVER_NAME'];
        }
    }
	//根据action
	private function getPathInfo(){
	    if($this->pathInfo == null){
            $pathInfo = $this->getRequestUri();
            if(($pos = strpos($pathInfo,'?' !== false))){
                $pathInfo = substr($pathInfo,0,$pos);
            }
            $pathInfo = urldecode($pathInfo);
            $scriptUrl = $this->getScriptUrl();
            $baseUrl = $this->getBaseUrl();
            if (strpos($pathInfo, $scriptUrl) === 0){
                $pathInfo = substr($pathInfo, strlen($scriptUrl));
            } else if ($baseUrl === '' || strpos($pathInfo, $baseUrl) === 0)
            {
                $pathInfo = substr($pathInfo, strlen($baseUrl));
            }
            else if (strpos($_SERVER['PHP_SELF'], $scriptUrl) === 0){
                $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
            }
            $this->pathInfo = trim($pathInfo, '/');
        }
        return $this->pathInfo;
	}
}