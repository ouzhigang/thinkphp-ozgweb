<?php
namespace app\common\model;

class DataClass extends Base {
    
	public static function deleteById($id) {
		$dc_list = parent::where("parent_id = " . $id)->select();
		foreach($dc_list as $v) {
			$child_count = parent::where("parent_id = " . $v["id"])->count();
			if($child_count > 0) {
				self::deleteById($v["id"]);
			}
			//删除该分类下面的对应数据
			Data::where("data_class_id = " . $v["id"])->delete();
			parent::where("id = " . $v["id"])->delete();
		}
		
	}
	
	public static function getById($id) {
		$dataclass = parent::where("id = " . $id)->find();
		$dataclass = $dataclass->toArray();
		if($dataclass["parent_id"] != 0)
			$dataclass["parent"] = self::getById($dataclass["parent_id"]);
		return $dataclass;
	}
	
	public static function listById($id) {		
		$dc_list = parent::all(function($query) use($id) {
			$query->where("parent_id = " . $id)->order([ "sort" => "desc", "id" => "desc" ]);
		});
		
		$list = [];
		foreach($dc_list as $v) {
			$item = $v->toArray();
			$item["children"] = [];
			
			$child_count = parent::where("parent_id = " . $item["id"])->count();
			if($child_count > 0) {
				$item["children"] = self::listById($item["id"]);
			}
			
			$list[] = $item;
		}
		return $list;
	}
	
	public static function getTreeSelector($type) {
		$data = [];
		$list = parent::all(function($query) use($type) {
			$query->where("parent_id = 0 and type = " . $type)->order(["sort" => "desc", "id" => "desc"]);
		});
		
		foreach($list as $v) {
			$data[] = [
				"id" => intval($v["id"]),
				"parent_id" => 0,
				"name" => $v["name"]
			];
			$res = parent::where("parent_id = " . $v["id"])->count();
			if($res) {
				self::treeSelector($data, $v["id"]);
			}
		}
		return $data;
	}
	
	protected static function treeSelector(&$data, $parent_id) {
		$list = parent::all(function($query) use($parent_id) {
			$query->where("parent_id = " . $parent_id)->order([ "sort" => "desc", "id" => "desc" ]);
		});
		
		foreach($list as $v) {
			$item = $v->toArray();
			$data[] = [
				"id" => intval($item["id"]),
				"parent_id" => intval($item["parent_id"]),
				"name" => $item["name"]
			];
			self::treeSelector($data, $item["id"]);
		}
	}

	public static function saveData($data, $id = 0) {
		if($id) {
			if($id == $data["parent_id"]) {
				return res_result(NULL, 1, "父级分类不能为当前选中分类");
			}
			
			parent::where("id = " . $id)->update($data);
			
			return res_result(NULL, 0, "更新成功");
		}
		else {
			unset($data["id"]);
			parent::create($data);
			
			return res_result(NULL, 0, "添加成功");
		}
	}
	
	public static function delById($id = 0) {
		
		$dataclass = parent::where("id = " . $id)->find();
		$dataclass = $dataclass->toArray();
		$child_count = parent::where("parent_id = " . $dataclass["id"])->count();
		
		if($child_count > 0)
			self::deleteById($dataclass["id"]);
		
		//删除该分类下面的对应数据
		Data::where("data_class_id = " . $dataclass["id"])->delete();
		parent::where("id = " . $dataclass["id"])->delete();
		
		return true;
	}
	
	public static function findById($id = 0) {
		$data = parent::where("id = " . $id)->find();
		return $data->toArray();
	}
	
	public static function getList($type = 0) {
		$data = parent::all(function($query) use($type) {
			$query->where("type = " . $type . " and parent_id = 0")->order([ "sort" => "desc", "id" => "desc" ]);
		});
		
		$list = [];		
		foreach($data as $v) {
			$item = $v->toArray();
			$item["children"] = [];
			
			$child_count = parent::where("parent_id = " . $item["id"])->count();
			if($child_count > 0)
				$item["children"] = self::listById($item["id"]);
			
			$list[] = $item;
		}
		return $list;
	}
	
}
