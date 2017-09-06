<?php
class indexController extends core_controller{
	public function indexAction(){
//	    print_r($_SERVER);exit;
//	    $this->_render('index.html',array('a' => '123'));
//	    $res = M('xyb_test_model')->insertOne();
        $selectRes = M('xyb_test_model')->getOne(array('id' => array('in',array(1,2,3)),'val' => array('like','%2')));
//        var_dump($res);
        var_dump($selectRes);
	    var_dump(core_db::sqlDebug());
	    //exit('abc');
		//$params = $this->_getParams();
		//print_R($params);
	}
	public function testAction(){
	    exit('abc');
    }
}