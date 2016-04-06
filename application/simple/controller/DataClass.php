<?php
namespace app\simple\controller;

class DataClass extends Base {
	
	public function getlist() {
		$type = I("request.type", 0, "intval");
		
		if(I("request.get_data", 0, "intval")) {			
			$data = D("DataClass")->getList($type);
			$r = [
				"code" => 0,
				"desc" => "请求成功",
				"data" => $data
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
			$row = D("DataClass")->findById($id);			
		}
		else {
			$row = [
				"id" => 0,
				"name" => "",
				"sort" => 0,
				"parent_id" => 0,
				"type" => I("request.type", 0, "intval")
			];			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["parent_id"] = I("post.parent_id", 0, "intval");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["type"] = I("post.type", 0, "intval");
			
			if($id != 0) {
				
				if($id == $row["parent_id"]) {
					$r = [
						"code" => 1,
						"desc" => "父级分类不能为当前选中分类"
					];
					\think\Response::type("json");
					return $r;
				}
				
				D("DataClass")->saveData($row, $id);
				$r = [
					"code" => 0,
					"desc" => "更新成功"
				];
				\think\Response::type("json");
				return $r;
			}
			else {				
				D("DataClass")->saveData($row);
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
	
	public function gettree() {
		$type = I("get.type", 0, "intval");
		$data = D("DataClass")->getTreeSelector($type);
		
		$r = [
			"code" => 0,
			"desc" => "请求成功",
			"data" => $data
		];
		\think\Response::type("json");
		return $r;
	}
	
	public function del() {
		
		$id = I("request.id", 0, "intval");
		D("DataClass")->delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
}
