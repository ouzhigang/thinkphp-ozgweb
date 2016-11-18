<?php
namespace app\mgr\controller;

class User extends Base {
	
	public function show() {
		
		//分页索引和每页显示数
		$get_data = input("param.get_data", NULL);
		if($get_data) {			
			$page = input("param.page", 1, "intval");
			$page_size = input("param.page_size", config("web_page_size"), "intval");
			
			$data = \app\common\model\User::getList($page, $page_size);
			return json(res_result($data, 0, "请求成功"));
		}
		
		return $this->fetch("show");
	}
	
	public function add() {
		
		$name = input("post.name", "", "str_filter");
		$pwd = input("post.pwd", "", "str_filter");
		$pwd2 = input("post.pwd2", "", "str_filter");
			
		$user = [
			"name" => $name,
			"pwd" => $pwd,
			"pwd2" => $pwd2,
		];
		$res = \app\common\model\User::saveData($user);
		return json($res);
	}
	
	public function del() {
		$id = input("param.id", 0, "intval");
		$res = \app\common\model\User::delById($id);
		
		return json($res);
	}
	
	public function updatepwd() {
		
		if(request()->isPOST()) {
			
			$old_pwd = input("post.old_pwd", "", "str_filter");
			$pwd = input("post.pwd", "", "str_filter");
			$pwd2 = input("post.pwd2", "", "str_filter");
			
			return json(\app\common\model\User::updatePwd($old_pwd, $pwd, $pwd2));
		}
		
		return $this->fetch("updatepwd");
	}
	
}
