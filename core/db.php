<?php
// 数据库连接

class core_db{

	private $dbConf = array();//整个数据库配置文件数组

	private $currentLink;//当前连接

	private $masterConf = array();//主库配置文件

	private $slaveConf = array();//从库配置文件

	private $databaseName; //当前配置数据库键名

	private static $linkList = array();//连接列表

	private static $instance = null;

	private static $sqlStr = '';

	private static $sqlErrorStr = '';


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
		$this->masterConf = $this->dbConf[$databaseName]['master'];
		$this->slaveConf = $this->dbConf[$databaseName]['slave'];
		$this->databaseName = $databaseName;
	}
	//获取主库连接
	private function getMasterLink(){
		if(isset(self::$linkList[$this->databaseName]['master']) && is_resource(self::$linkList[$this->databaseName]['master'])){
			return self::$linkList[$this->databaseName]['master'];
		}
		return $this->getConnect(current($this->masterConf),'master');
	}
	//获取从库连接
	private function getSlaveLink(){
		if(isset(self::$linkList[$this->databaseName]['slave']) && is_resource(self::$linkList[$this->databaseName]['slave'])){
			return self::$linkList[$this->databaseName]['slave'];
		}
		//实现权重随机获取从库配置文件
		$totalWeight = 0;
		$configArr = array();
		foreach($this->slaveConf as $v){
			$totalWeight += $v['weight'];
		}
		foreach($this->slaveConf as $v){
			$tmpRandNum = mt_rand(1,$totalWeight);
			if($tmpRandNum <= $v['weight']){
				$configArr = $v;
				break;
			}else{
				$totalWeight -= $v['weight'];
			}
		}
		return $this->getConnect($configArr,'slave');
	}
	//mysql连接
	public function getConnect($config,$dbType){
		$host = $config['host']?$config['host']:'localhost';
		$port = $config['port']?$config['port']:'3306';
		$user = $config['user']?$config['user']:'root';
		$passwd = $config['passwd'];
		$db = $config['db'];
		$charset = $config['charset']?$config['charset']:'utf8';
		$conn = mysqli_connect($host,$user,$passwd,$db,$port);
		if($erron = mysqli_connect_errno()){
			die('连接错误；'.mysqli_connect_error().'['.$erron.']');
		}
		mysqli_set_charset($conn,$charset);
		self::$linkList[$this->databaseName][$dbType] = $conn;
		return $conn;
	}
	//执行sql语句
	public function query($queryStr,$forceMaster = false){
		$startTime = $this->getMicroTime();
		if(($isSelect = (!strncasecmp($queryStr, 'select', 6))) && !$forceMaster){//走从库
			$this->currentLink = $this->getSlaveLink();
		}else{//走主库
			$this->currentLink = $this->getMasterLink();
		}

		$queryStrEscape = mysqli_real_escape_string($this->currentLink,$queryStr);
		$queryRes = mysqli_query($this->currentLink,$queryStrEscape);

		if(!$queryRes){
			$errMesg = mysqli_error($this->currentLink);
			error_log($queryStrEscape.'|'.$errMesg,'/var/php/sqlLog/'.date('Y-m-d H:i:s').'.log');
			// throw new Exception();
		}
		if(DEBUG){
            $errMesg && self::$sqlErrorStr .= $errMesg;
			$endTime = $this->getMicroTime();
			self::$sqlStr .= $queryStr."\n".'|'.(float)((float)$endTime - (float)$startTime)."\n";
		}
		//根据sql语句返回执行结果
		if($isSelect){
			while($row = $this->fetchAssoc($queryRes)){
			    $rows[] = $row;
			}
			mysqli_free_result($queryRes);//释放结果集
			return $rows;
		}else if(!strncasecmp($queryStr, 'update', 6)){
			return $this->getAffectRows();
		}else if(!strncasecmp($queryStr, 'insert', 6)){
			return $this->getInsertId();
		}else if(!strncasecmp($queryStr, 'delete', 6)){
			return $this->getAffectRows();
		}
	}
	//打印sql
	public static function sqlDebug(){
		return self::$sqlStr."\n\n".self::$sqlErrorStr;
	}
	//获取毫秒级的时间戳
	private function getMicroTime(){
		list($usec,$sec) = explode(' ',microtime());
		return (float)((float)$usec + (float)$sec);
	}
	//获取受影响的行
	public function getAffectRows(){
		return mysqli_affected_rows($this->currentLink);
	}
	//获取插入的返回主键id
	public function getInsertId(){
		return mysqli_insert_id($this->currentLink);
	}
	//从返回结果中逐行获取关联数组数据
	public function fetchAssoc($queryRes){
		return mysqli_fetch_assoc($queryRes);
	}
	//从返回结果中逐行获取关联数组数据
	public function fetchRow(){
		return mysqli_fetch_row($this->currentLink);
	}
}