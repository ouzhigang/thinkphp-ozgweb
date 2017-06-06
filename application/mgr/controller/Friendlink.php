<?php
namespace app\mgr\controller;

class Friendlink extends Base {
	
	public function show() {
		
		//分页索引和每页显示数
		$get_data = input("param.get_data", NULL);
		if($get_data) {
			$page = input("param.page", 1, "intval");
			$page_size = input("param.page_size", config("web_page_size"), "intval");
			
			$data = \app\common\model\Friendlink::getList($page, $page_size);
			return json(res_result($data, 0, "请求成功"));
		}
		
		return $this->fetch("show");
	}
	
	public function get() {
		$id = input("param.id", 0, "intval");
		$data = \app\common\model\Friendlink::findById($id);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$id = input("param.id", 0, "intval");
		
		$row = [];
		$row["name"] = input("post.name", "", "str_filter");
		$row["url"] = input("post.url", "", "str_filter");	
		$row["picture"] = input("post.picture", "", "str_filter");
		$row["sort"] = input("post.sort", 0, "intval");
		$row["is_picture"] = input("post.is_picture", 0, "intval");
		
		if($id != 0) {
			$res = \app\common\model\Friendlink::saveData($row, $id);
			return json($res);
		}
		else {
			$res = \app\common\model\Friendlink::saveData($row);
			return json($res);
		}
	}
	
	public function del() {
		$id = input("param.id", 0, "intval");
		\app\common\model\Friendlink::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
