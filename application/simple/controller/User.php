<?php
namespace app\simple\controller;

class User extends Base {
	
	public function getlist() {
		
		//分页索引和每页显示数
		$get_data = input("request.get_data", NULL);
		if($get_data) {			
			$page = input("request.page", 1, "intval");
			$page_size = input("request.page_size", config("web_page_size"), "intval");
			
			$r = [
				"code" => 0,
				"desc" => "请求成功",
				"data" => \app\common\model\User::getList($page, $page_size)
			];
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
		
		if(IS_POST) {
			$name = input("post.name", "", "str_filter");
			$pwd = input("post.pwd", "", "str_filter");
			$pwd2 = input("post.pwd2", "", "str_filter");
			if(!$name) {
				$r = [
					"code" => 1,
					"desc" => "用户名不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			if(!$pwd) {
				$r = [
					"code" => 1,
					"desc" => "密码不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			if($pwd != $pwd2) {
				$r = [
					"code" => 1,
					"desc" => "确认密码不正确"
				];
				\think\Response::type("json");
				return $r;
			}
			
			$user = [
				"name" => $name,
				"pwd" => $pwd,
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
			
			if(!$old_pwd) {
				$r = [
					"code" => 1,
					"desc" => "旧密码不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			if(!$pwd) {
				$r = [
					"code" => 1,
					"desc" => "新密码不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			if($pwd != $pwd2) {
				$r = [
					"code" => 1,
					"desc" => "确认密码不正确"
				];
				\think\Response::type("json");
				return $r;
			}
			
			$r = \app\common\model\User::updatePwd($old_pwd, $pwd, $pwd2);
			
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("updatepwd");
	}
	
}
