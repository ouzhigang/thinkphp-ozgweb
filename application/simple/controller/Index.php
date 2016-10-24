<?php
namespace app\simple\controller;

use \think\Config;
use \think\captcha\Captcha;

class Index extends \app\common\controller\Base {
	
	public function getvcode($id = "") {
				
		$captcha = new Captcha((array)Config::get('captcha'));
        return $captcha->entry($id);
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
		
		if(request()->isPOST()) {
			$name = input("param.name", NULL, "str_filter");
			$pwd = input("param.pwd", NULL, "str_filter");
			
			//提交登录
			$remember = input("param.remember", 0, "intval");
			$vcode = input("param.vcode", NULL, "str_filter");			
			
			$data = \app\common\model\User::adminLogin($name, $pwd, $vcode, $remember);
			$r = \think\Response::create($data, "json");
			$r->send();
			return NULL;
		}
		else
			return $this->fetch("login");
    }	
	
}
