<?php
namespace app\simple\controller;

use \think\Response;

class Base extends \app\common\controller\Base {
    
	protected $beforeActionList = [
        "checkLogin",
    ];
	
	protected function checkLogin() {
		
		$curr_action = strtolower(ACTION_NAME);
		$user = NULL;
		
		//检查是否已登录		
		if(!cookie("curr_user_name")) {
			if(!session('?user')) {
				if(strpos($curr_action, "/index/login") === false) {
					//没有登录
					
					header(strtolower("location: " . config("web_root") . "simple/index/login"));
					exit();
				}
			}
			else {
				//如果是已登录状态，停留在登录页面的话，就跳到后台首页
				if(strpos($curr_action, "/index/login") !== false) {
					header(strtolower("location: " . config("web_root") . "simple/other/main"));
					exit();
				}
				
				$user = session("user");
			}
		}
		else {
			$curr_user_name = \utility\Encrypt::decode(cookie("curr_user_name"));
			
			$user =  \app\common\model\User::findByName($curr_user_name);
			session("user", $user);
		}
		
		if(!is_null($user)) {
			$this->assign("sess_user_name", $user["name"]);
		}
		
	}
	
}
