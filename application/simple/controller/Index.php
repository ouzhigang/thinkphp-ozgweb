<?php
namespace app\simple\controller;

class Index extends \app\common\controller\Base {
	
	public function getvcode() {
				
		$verify = new \org\Verify();
		$verify->fontSize = 14;
		$verify->length = 4;
		$verify->useNoise = false;
		$verify->codeSet = '0123456789';
		$verify->imageW = 120;
		$verify->imageH = 30;
		//$verify->expire = 600;
		$verify->entry(1);
		
		return NULL;
	}
	
    public function login() {
		if(cookie("curr_user_name")) {
			//一周内自动登录		
			$name = str_filter(cookie("curr_user_name"));			
			$name = \utility\Encrypt::decode($name);
			
			$wq = [
				"is_admin" => 1
			];
			$user = \app\common\model\User::findByName($name, $wq);					
			unset($user["pwd"]);
			session("user", $user);
			$user["err_login"] = 0;
		
			\app\common\model\User::saveData($user, $user["id"]);
		
			header("location: ../other/main");
			return NULL;
		}
		
		if(IS_POST) {
			$name = input("request.name", NULL, "str_filter");
			$pwd = input("request.pwd", NULL, "str_filter");
			
			//提交登录
			$remember = input("request.remember", 0, "intval");
			$vcode = input("request.vcode", NULL, "str_filter");			
			
			$r = \app\common\model\User::adminLogin($name, $pwd, $vcode, $remember);
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("login");
    }	
	
}
