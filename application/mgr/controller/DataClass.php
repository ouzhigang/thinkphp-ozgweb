<?php
namespace app\mgr\controller;

class DataClass extends Base {
	
	public function show() {
		$type = input("param.type", 0, "intval");
		
		if(input("param.get_data", 0, "intval")) {			
			$data = \app\common\model\DataClass::getList($type);
			return json(res_result($data, 0, "请求成功"));
		}
		
		return $this->fetch("show");
	}
	
	public function get() {
		$id = input("param.id", 0, "intval");
		$data = \app\common\model\DataClass::getById($id);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		if(request()->isPOST()) {
			$id = input("param.id", 0, "intval");
			
			$row = [];
			$row["name"] = input("post.name", "", "str_filter");
			$row["parent_id"] = input("post.parent_id", 0, "intval");
			$row["sort"] = input("post.sort", 0, "intval");
			$row["type"] = input("post.type", 0, "intval");
			
			if($id != 0) {				
				return json(\app\common\model\DataClass::saveData($row, $id));
			}
			else {
				return json(\app\common\model\DataClass::saveData($row));
			}
		}
		return $this->fetch("add");
	}
	
	public function gettree() {
		$type = input("param.type", 0, "intval");
		$data = \app\common\model\DataClass::getTreeSelector($type);
		
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function del() {
		
		$id = input("param.id", 0, "intval");
		\app\common\model\DataClass::delById($id);
		return json(res_result(NULL, 0, "请求成功"));
	}
	
}
