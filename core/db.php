<?php
// 数据库连接

class core_db{

	private $dbConf = array();//整个数据库配置文件数组

	private $currentLink;//当前连接

	private $masterConf = array();//主库配置文件

	private $slaveConf = array();//从库配置文件

	private static $linkList = array();//连接列表

	private static $instance = null;

	public function instance($databaseName){
		if(is_null(self::$instance)){
			self::$instance = new self($databaseName);
		}
		return self::$instance;
	}
	public function __construct($databaseName){
		$this->dbConf = require_once(ROOT.'/config/db.cfg.php');
		if(!isset($this->dbConf[$databaseName])){
			throw new Exception("不存在当前数据库配置");
		}
		$this->masterConf = $this->dbConf['master'];
	}
	private function getMasterLink(){

	}

	private function getSlaveLink(){
		
	}
	//执行sql语句
	public function query(){
		
	}
	//获取受影响的行
	public function getAffectRows(){

	}
	//获取插入的返回主键id
	public function getInsertId(){
		
	}

	//从返回结果中逐行获取数据
	public function fetchRow(){
		
	}
}