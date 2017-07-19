<?php
class common_test_model extends core_store{
	public static function model($tableName,$databaseName = DEFAULT_DATABASE){
		if(empty($tableName)){
			$tableName = substr(__CLASS__, strpos('_', __CLASS__),strrpos('_', __CLASS__));
		}
		parent::model($tableName,$databaseName);
	}
}