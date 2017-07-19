<?php
// 数据库连接

class core_db{

	private $dbConf = array();

	private $currentLink;//当前连接

	private $masterConf = array();

	private $slaveConf = array();

	private static $instance = null;

	public function instance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}

}