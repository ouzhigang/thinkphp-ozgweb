<?php
namespace app\mgr\controller;

class User extends Base {
	
	public function show() {
		
		$page = input("param.page", 1, "intval");
		$page_size = input("param.page_size", config("web_page_size"), "intval");
		
		$data = \app\common\model\User::getList($page, $page_size);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$name = input("post.name", "", "str_filter");
		$pwd = input("post.pwd", "", "str_filter");
				
		$user = [
			"name" => $name,
			"pwd" => $pwd,
		];
		$res = \app\common\model\User::saveData($user);
		return json($res);
	}
	
	public function del() {
		$ids = input("param.ids", "", "str_filter");
		$ids = explode(",", $ids);
		$res = \app\common\model\User::delById($ids);
		
		return json($res);
	}
	
	public function updatepwd() {
		
		$old_pwd = input("post.old_pwd", "", "str_filter");
		$pwd = input("post.pwd", "", "str_filter");
		$pwd2 = input("post.pwd2", "", "str_filter");
			
		return json(\app\common\model\User::updatePwd($old_pwd, $pwd, $pwd2));
	}
	
}
