<?php
class xyb_test_model extends core_store{
	
	public function getOne($where){
		return $this->where($where)->select();
	}

    public function insertOne(){
	    $data =array(
	        array('val' => 123,'content' => 'abc'),
            array('val' => 124,'content' => 'abc'),
            array('val' => 125,'content' => 'abc'),
        );
        return $this->save($data);
    }
}