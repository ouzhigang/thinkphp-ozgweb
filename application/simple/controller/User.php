<?php
namespace app\simple\controller;

use \think\Response;
use \think\Request;

class User extends Base {
	
	public function getlist() {
		
		//分页索引和每页显示数
		$get_data = input("request.get_data", NULL);
		if($get_data) {			
			$page = input("request.page", 1, "intval");
			$page_size = input("request.page_size", config("web_page_size"), "intval");
			
			return res_result(\app\common\model\User::getList($page, $page_size), 0, "请求成功");
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
		
		if(Request::instance()->isPOST()) {
			$name = input("post.name", "", "str_filter");
			$pwd = input("post.pwd", "", "str_filter");
			$pwd2 = input("post.pwd2", "", "str_filter");
			
			$user = [
				"name" => $name,
				"pwd" => $pwd,
				"pwd2" => $pwd2,
			];
			$data = \app\common\model\User::saveData($user);
			
			$r = \think\Response::create($data, "json");
			$r->send();
			return NULL;
		}
		
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("request.id", 0, "intval");
		$data = \app\common\model\User::delById($id);
		
		$r = \think\Response::create($data, "json");
		$r->send();
		return NULL;
	}
	
	public function updatepwd() {
		
		if(Request::instance()->isPOST()) {
			
			$old_pwd = input("post.old_pwd", "", "str_filter");
			$pwd = input("post.pwd", "", "str_filter");
			$pwd2 = input("post.pwd2", "", "str_filter");
			
			return \app\common\model\User::updatePwd($old_pwd, $pwd, $pwd2);
		}
		
		return $this->fetch("updatepwd");
	}
	
}
