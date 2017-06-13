<?php
namespace app\mgr\controller;

class User extends Base {
	
	public function show() {
		
		$page = input("param.page", 1, "intval");
		$page_size = input("param.page_size", config("web_page_size"), "intval");
		
		$data = \app\common\model\User::getList($page, $page_size);
		$this->assign("data", $data);
		
		//分页导航
		$page_first = config("web_root") . "mgr/user/show?page=1";
		$page_prev = config("web_root") . "mgr/user/show?page=" . ($page <= 1 ? 1 : $page - 1);
		$page_next = config("web_root") . "mgr/user/show?page=" . ($page >= $data["page_count"] ? $data["page_count"] : $page + 1);
		$page_last = config("web_root") . "mgr/user/show?page=" . $data["page_count"];
		
		$this->assign("page_first", $page_first);
		$this->assign("page_prev", $page_prev);
		$this->assign("page_next", $page_next);
		$this->assign("page_last", $page_last);		
		//分页导航 end
		
		return $this->fetch("show");
	}
	
	public function add() {
		if(request()->isPOST()) {
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
		
		return $this->fetch("add");
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
