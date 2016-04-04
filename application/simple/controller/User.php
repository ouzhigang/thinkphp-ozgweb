<?php
namespace app\simple\controller;

class User extends Base {
	
	public function getlist() {
		
		//分页索引和每页显示数
		$get_data = I("request.get_data", NULL);
		if($get_data) {			
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("web_page_size"), "intval");
			$this->resSuccess("请求成功", D("User")->getList($page, $page_size));
		}
				
		$this->display();
	}
	
	public function add() {
		
		if(IS_POST) {
			$name = I("post.name", "", "str_filter");
			$pwd = I("post.pwd", "", "str_filter");
			$pwd2 = I("post.pwd2", "", "str_filter");
			if(!$name)
				$this->resFail(1, "用户名不能为空");
			if(!$pwd)
				$this->resFail(1, "密码不能为空");
			if($pwd != $pwd2)
				$this->resFail(1, "确认密码不正确");
			
			$total = D("User")->field("count(id) as total")->where("name = '" . $name . "'")->find();
			$total = $total["total"];
			if($total > 0)
				$this->resFail(1, "该用户已存在");
				
			$user = array(
				"name" => $name,
				"pwd" => $pwd,
				"add_time" => time(),
				"is_admin" => 1
			);
			D("User")->add($user);
			$this->resSuccess("添加成功");
		}
		
		$this->display();		
	}
	
	public function del() {
		$id = I("request.id", 0, "intval");
		$r = D("User")->del($id);
		$this->ajaxReturn($r, "JSON");
	}
	
	public function updatepwd() {
		
		if(IS_POST) {
			$curr_user = session("user");
			$old_pwd = I("post.old_pwd", "", "str_filter");
			$pwd = I("post.pwd", "", "str_filter");
			$pwd2 = I("post.pwd2", "", "str_filter");
			
			if(!$old_pwd)
				$this->resFail(1, "旧密码不能为空");
			if(!$pwd)
				$this->resFail(1, "新密码不能为空");
			if($pwd != $pwd2)
				$this->resFail(1, "确认密码不正确");
			
			$user = D("User")->where("name = '" . $curr_user["name"] . "' and pwd = '" . $old_pwd . "'")->find();
			if($user) {
				$user["pwd"] = $pwd;
				D("User")->save($user);
				$this->resSuccess("修改密码成功");
			}
			else
				$this->resFail(1, "旧密码不正确");
		}
		
		$this->display();
	}
	
}
