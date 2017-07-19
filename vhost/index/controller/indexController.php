<?php
class indexController extends core_controller{
	public function indexAction(){
		$params = $this->_getParams();
		print_R($params);
	}
}