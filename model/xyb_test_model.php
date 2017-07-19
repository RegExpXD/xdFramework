<?php
class xyb_test_model extends core_store{
	public function __construct($tableName,$databaseName = DEFAULT_DATABASE){
		if(empty($tableName)){
			$tableName = substr(__CLASS__, strpos('_', __CLASS__),strrpos('_', __CLASS__));
		}
		parent::model($tableName,$databaseName);
	}
}