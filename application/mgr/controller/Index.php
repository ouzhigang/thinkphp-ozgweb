<?php
namespace app\mgr\controller;

use \think\Config;
use \think\captcha\Captcha;

class Index extends \app\common\controller\Base {
	
	public function getvcode($id = "") {
				
		$captcha = new Captcha((array)Config::get('captcha'));
        return $captcha->entry($id);
	}
	
    public function login() {
		
		$name = input("param.name", NULL, "str_filter");
		$pwd = input("param.pwd", NULL, "str_filter");
			
		//提交登录
		$vcode = input("param.vcode", NULL, "str_filter");			
			
		$res = \app\common\model\User::login($name, $pwd, $vcode);
		return json($res);
    }	
	
}
