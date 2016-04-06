<?php
namespace app\simple\controller;

class Friendlink extends Base {
	
	public function getlist() {
		
		$page = I("request.page", 1, "intval");
		$page_size = I("request.page_size", C("web_page_size"), "intval");
		$res_data = D("Friendlink")->getList($page, $page_size);
		$this->assign("data", $res_data);
			
		return $this->fetch("getlist");
	}
	
	public function add() {
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("Friendlink")->findById($id);			
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
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["url"] = I("post.url", "", "str_filter");	
			$row["picture"] = I("post.picture", "", "str_filter");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["is_picture"] = I("post.is_picture", 0, "intval");
			
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
				D("Friendlink")->saveData($row, $id);
				$r = [
					"code" => 0,
					"desc" => "更新成功"
				];
				\think\Response::type("json");
				return $r;
			}
			else {
				D("Friendlink")->saveData($row);
				
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
		$id = I("request.id", 0, "intval");
		D("Friendlink")->delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
}
