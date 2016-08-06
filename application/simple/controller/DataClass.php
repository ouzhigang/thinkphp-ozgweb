<?php
namespace app\simple\controller;

use \think\Response;

class DataClass extends Base {
	
	public function getlist() {
		$type = input("request.type", 0, "intval");
		
		if(input("request.get_data", 0, "intval")) {			
			$data = \app\common\model\DataClass::getList($type);
			return Response::result($data, 0, "请求成功", "json");
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
		$id = input("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = \app\common\model\DataClass::findById($id);			
		}
		else {
			$row = [
				"id" => 0,
				"name" => "",
				"sort" => 0,
				"parent_id" => 0,
				"type" => input("request.type", 0, "intval")
			];			
		}
		
		if(IS_POST) {
			
			$row["name"] = input("post.name", "", "str_filter");
			$row["parent_id"] = input("post.parent_id", 0, "intval");
			$row["sort"] = input("post.sort", 0, "intval");
			$row["type"] = input("post.type", 0, "intval");
			
			if($id != 0) {
				
				if($id == $row["parent_id"]) {
					return Response::result(NULL, 1, "父级分类不能为当前选中分类", "json");
				}
				
				\app\common\model\DataClass::saveData($row, $id);
				return Response::result(NULL, 0, "更新成功", "json");
			}
			else {				
				\app\common\model\DataClass::saveData($row);
				return Response::result(NULL, 0, "添加成功", "json");
			}
		}
		
		$this->assign("row", $row);
		return $this->fetch("add");
	}
	
	public function gettree() {
		$type = input("get.type", 0, "intval");
		$data = \app\common\model\DataClass::getTreeSelector($type);
		
		return Response::result($data, 0, "请求成功", "json");
	}
	
	public function del() {
		
		$id = input("request.id", 0, "intval");
		\app\common\model\DataClass::delById($id);
		return Response::result(NULL, 0, "删除成功", "json");
	}
	
}
