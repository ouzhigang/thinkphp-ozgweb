<?php
namespace app\simple\controller;

use \think\Response;

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
		$data = \app\common\model\DataClass::findById($id);		
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
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
	
	public function gettree() {
		$type = input("get.type", 0, "intval");
		$data = \app\common\model\DataClass::getTreeSelector($type);
		
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function del() {
		
		$id = input("param.id", 0, "intval");
		\app\common\model\DataClass::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
