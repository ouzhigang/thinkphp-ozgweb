<?php
namespace app\simple\controller;

class Friendlink extends Base {
	
	public function getlist() {
		
		$page = input("request.page", 1, "intval");
		$page_size = input("request.page_size", config("web_page_size"), "intval");
		$res_data = \app\common\model\Friendlink::getList($page, $page_size);
		$this->assign("data", $res_data);
			
		return $this->fetch("getlist");
	}
	
	public function add() {
		$id = input("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = \app\common\model\Friendlink::findById($id);			
		}
		else {
			$row = [
				"id" => 0,
				"name" => "",
				"url" => "",
				"picture" => "",
				"sort" => 0,
				"is_picture" => 0
			];			
		}
		
		if(IS_POST) {
			$row = [];
			$row["name"] = input("post.name", "", "str_filter");
			$row["url"] = input("post.url", "", "str_filter");	
			$row["picture"] = input("post.picture", "", "str_filter");
			$row["sort"] = input("post.sort", 0, "intval");
			$row["is_picture"] = input("post.is_picture", 0, "intval");
			
			if(!$row["name"]) {
				$r = [
					"code" => 1,
					"desc" => "名称不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			elseif(!$row["url"]) {
				$r = [
					"code" => 1,
					"desc" => "URL不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			
			if($id != 0) {				
				\app\common\model\Friendlink::saveData($row, $id);
				$r = [
					"code" => 0,
					"desc" => "更新成功"
				];
				\think\Response::type("json");
				return $r;
			}
			else {
				\app\common\model\Friendlink::saveData($row);
				
				$r = [
					"code" => 0,
					"desc" => "添加成功"
				];
				\think\Response::type("json");
				return $r;
			}
		}
		
		$this->assign("row", $row);
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("request.id", 0, "intval");
		\app\common\model\Friendlink::delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
}
