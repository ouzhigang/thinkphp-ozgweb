<?php
namespace app\mgr\controller;

class Index extends \app\BaseController {
	
	public function getvcode($id = "") {				
		return captcha($id);
	}
	
    public function login() {
		
		$name = input("param.name/s", "");
		$pwd = input("param.pwd/s", "");
			
		//提交登录
		$vcode = input("param.vcode/s", "");			
			
		$res = \app\common\model\User::login($name, $pwd, $vcode);
		return json($res);
    }	
	
}
