<?php
//错误异常自定义处理类
class core_handler{
	//自定义错误处理处理函数
	public function errorHandler($errorNo,$errorStr,$errorFile,$errorLine){
		if($errorNo > E_NOTICE){//这里可以写日志文件
			$this->displayError($errorNo,$errorStr,$errorFile,$errorLine);
		}
	}
	//自定义异常处理函数，处理没有使用try catch块的异常处理
	public function exceptionHandler($exception){
		$this->displayException($exception);
	}

	public function displayError($errorNo,$errorStr,$errorFile,$errorLine){
		$trace = debug_backtrace();
		if(DEBUG){
			$trace = debug_backtrace();
			if(count($trace) > 3){
				$trace = array_slice($trace, 3);
			}
			foreach($trace as $k => $v){
				if(!isset($v['file'])){
					$v['file'] = 'unknown';
				}
				if(!isset($v['function'])){
					$v['function'] = 'unknown';
				}
				if(!isset($v['line'])){
					$v['line'] = 'unknown';
				}
				if(!isset($v['class'])){
					$v['class'] = 'unknown';
				}
				echo "#{$k}{$v['file']}({$v['line']})-----{$v['class']}->{$v['function']}\n";
			}
		}else{
			echo "phpError[$errorNo]\n$errorStr";
		}

	}

	public function displayException($exception){
		if(DEBUG){
			echo '#'.$exception->getFile().'('.$exception->getLine().')----message:'.$exception->getMessage().'-----trace:'.$exception->getTraceAsString();
		}else{
			echo "phpError[".$exception->getMessage()."]\n$errorStr";
		}
	}
}