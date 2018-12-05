<?php
namespace app\mgr\controller;

use \think\captcha\Captcha;

class Index extends \app\common\controller\Base {
	
	public function getvcode($id = "") {
				
		$captcha = new Captcha((array)\think\facade\Config::pull('captcha'));
        return $captcha->entry($id);
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
