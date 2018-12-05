<?php
namespace app\mgr\controller;

class DataClass extends Base {
	
	public function show() {
		$type = input("param.type/d", 0);
		
		$data = \app\common\model\DataClass::getList($type);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function get() {
		$id = input("param.id/d", 0);
		$data = \app\common\model\DataClass::getById($id);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$id = input("param.id/d", 0);
			
		$row = [
			"name" => input("post.name/s", ""),
			"parent_id" => input("post.parent_id/d", 0),
			"sort" => input("post.sort/d", 0),
			"type" => input("post.type/d", 0),
		];
			
		if($id != 0) {				
			return json(\app\common\model\DataClass::saveData($row, $id));
		}
		else {
			return json(\app\common\model\DataClass::saveData($row));
		}
	}
	
	public function del() {
		
		$id = input("param.id/d", 0);
		\app\common\model\DataClass::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
