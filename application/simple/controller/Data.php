<?php
namespace app\simple\controller;

class Data extends Base {
	
	public function getlist() {
		
		$get_data = I("request.get_data", NULL);
		if($get_data) {
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("web_page_size"), "intval");
			$type = I("request.type", 1, "intval");
			$res_data = D("Data")->getList($page, $page_size, $type);
			
			$r = [
				"code" => 0,
				"desc" => "请求成功",
				"data" => $res_data
			];
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
				
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("Data")->where("id = " . $id)->find();			
		}
		else {
			$row = [
				"id" => 0,
				"name" => "",
				"content" => "",
				"dataclass_id" => 0,
				"sort" => 0,
				"type" => I("request.type", 0, "intval"),
				"picture" => ""
			];			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["content"] = I("post.content", "", "str_filter");	
			$row["dataclass_id"] = I("post.dataclass_id", 0, "intval");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["type"] = I("post.type", 0, "intval");		
			$row["picture"] = "";
			
			if(!$row["name"]) {
				$r = [
					"code" => 1,
					"desc" => "名称不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			elseif(!$row["content"]) {
				$r = [
					"code" => 1,
					"desc" => "内容不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			
			if($id != 0) {				
				D("Data")->saveData($row, $id);
				$r = [
					"code" => 0,
					"desc" => "更新成功"
				];
				\think\Response::type("json");
				return $r;
			}
			else {				
				D("Data")->saveData($row);
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
		D("Data")->delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
}
