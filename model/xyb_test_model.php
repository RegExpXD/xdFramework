<?php
class xyb_test_model extends core_store{
	
	public function getOne(){
		$this->where()->find();
	}
}