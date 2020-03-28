<?php
namespace app\mgr\controller;

use \think\App;

class Other extends \app\BaseController {
	
	protected $middleware = [ '\app\middleware\UserCheck' ];
	
	public function logout() {
		$res = \app\common\model\User::logout();
	
		return json($res);
	}
	
}
