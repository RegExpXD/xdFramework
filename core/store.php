<?php
//表关系映射，增删改查方法
class core_store{

	public $queryStr;

	public $whereStr;

	public $limitStr;

	public $orderStr;

	public $havingStr;

	public $fieldStr;

	public function field($fieldStr){
		$this->fieldStr = $fieldStr;
	}

	public function where($args){
		$str = '';
		if(is_array($args)){

		}else{
			$str = $args;
		}
		$this->whereStr = $args;
	}

	public function limit(){

	}

	public function join(){

	}

	public function order($orderStry){

	}

	public function order($orderStry){

	}

	private function getQueryStr(){

	}
	public function find(){
		$dbInstance = core_db::instance()->query($this->queryStr);
	}

	public function select(){
		
	}

	public function update(){
		
	}

	public function save(){
		
	}
}