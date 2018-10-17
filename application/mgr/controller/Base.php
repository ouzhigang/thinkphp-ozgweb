<?php
namespace app\mgr\controller;

class Base extends \app\common\controller\Base {
    
	protected $beforeActionList = [
        "checkLogin",
    ];
	
	protected function checkLogin() {
		
		//检查是否已登录		
		if(!session('?user')) {
			echo json_encode(res_result(NULL, 1, "请先登录后台"));
			exit();			
		}
		
	}
	
}
