<?php
namespace app\simple\controller;

use \think\Response;

class User extends Base {
	
	public function getlist() {
		
		//分页索引和每页显示数
		$get_data = input("request.get_data", NULL);
		if($get_data) {			
			$page = input("request.page", 1, "intval");
			$page_size = input("request.page_size", config("web_page_size"), "intval");
			
			return Response::result(\app\common\model\User::getList($page, $page_size), 0, "请求成功", "json");
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
		
		if(IS_POST) {
			$name = input("post.name", "", "str_filter");
			$pwd = input("post.pwd", "", "str_filter");
			$pwd2 = input("post.pwd2", "", "str_filter");
			
			$user = [
				"name" => $name,
				"pwd" => $pwd,
				"pwd2" => $pwd2,
			];
			$r = \app\common\model\User::saveData($user);
			
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("request.id", 0, "intval");
		$r = \app\common\model\User::delById($id);
		
		\think\Response::type("json");
		return $r;
	}
	
	public function updatepwd() {
		
		if(IS_POST) {
			
			$old_pwd = input("post.old_pwd", "", "str_filter");
			$pwd = input("post.pwd", "", "str_filter");
			$pwd2 = input("post.pwd2", "", "str_filter");
			
			return \app\common\model\User::updatePwd($old_pwd, $pwd, $pwd2);
		}
		
		return $this->fetch("updatepwd");
	}
	
}
