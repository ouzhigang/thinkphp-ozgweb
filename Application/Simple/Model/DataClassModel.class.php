<?php
namespace Simple\Model;
use Think\Model;

class DataClassModel extends Model {
    
	static function deleteById($id) {
		$m = M("DataClass");
		$dc_list = $m->where("parent_id = " . $id)->select();
		foreach($dc_list as $v) {
			$child_count = $m->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
			$child_count = $child_count["total"];
			if($child_count > 0)
				deleteById($v["id"]);
			
			//删除该分类下面的对应数据
			$m2 = M("Data");
			$m2->where("dataclass_id = " . $v["id"])->delete();
			$m->where("id = " . $v["id"])->delete();
		}
		
	}
	
	static function getById($id) {		
		$m = M("DataClass");
		$dataclass = $m->where("id = " . $id)->find();
		if($dataclass["parent_id"] != 0)
			$dataclass["parent"] = getById($dataclass["parent_id"]);
		return $dataclass;
	}
	
	static function listById($id) {
		$m = M("DataClass");
		
		$dc_list = $m->where("parent_id = " . $id)->order("sort desc, id desc")->select();
		foreach($dc_list as &$v) {
			$child_count = $m->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
			$child_count = $child_count["total"];
			if($child_count > 0)
				$v["children"] = listById($v["id"]);
			
		}
		return $dc_list;
	}
	
}
