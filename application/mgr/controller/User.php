<?php
namespace app\mgr\controller;

class User extends Base {
	
	public function show() {
		
		$page = input("param.page/d", 1);
		$page_size = input("param.page_size/d", config("web_page_size"));
		
		$data = \app\common\model\User::getList($page, $page_size);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$name = input("post.name/s", "");
		$pwd = input("post.pwd/s", "");
				
		$user = [
			"name" => $name,
			"pwd" => $pwd,
		];
		$res = \app\common\model\User::saveData($user);
		return json($res);
	}
	
	public function del() {
		$ids = input("param.ids", "");
		$ids = explode(",", $ids);
		$res = \app\common\model\User::delById($ids);
		
		return json($res);
	}
	
	public function updatepwd() {
		
		$old_pwd = input("post.old_pwd/s", "");
		$pwd = input("post.pwd/s", "");
		$pwd2 = input("post.pwd2/s", "");
			
		return json(\app\common\model\User::updatePwd($old_pwd, $pwd, $pwd2));
	}
	
}
