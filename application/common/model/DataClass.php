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
		
		foreach($dc_list as &$v) {
			$child_count = parent::where("parent_id = " . $v["id"])->count();
			if($child_count > 0) {
				$v["children"] = self::listById($v["id"]);
			}
		}
		return $dc_list;
	}
	
	/*public static function getTreeSelector($type) {
		$data = [];
		$list = parent::all(function($query) use($type) {
			$query->where("parent_id = 0 and type = " . $type)->order([ "sort" => "desc", "id" => "desc" ])
		});
		
		foreach($list as &$v) {
						
			$res = parent::where("parent_id = " . $v["id"])->count();
			if($res > 0) {
				self::treeSelector($v);
			}
			
			$data[] = $v;
		}
		return $data;
	}	
	protected static function treeSelector(&$parent_row) {
		$list = parent::all(function($query) use($parent_row) {
			$query->where("parent_id = " . $parent_row["id"])->order([ "sort" => "desc", "id" => "desc" ])
		});
		
		foreach($list as &$v) {
			$res = parent::where("parent_id = " . $v["id"])->count();
			if($res > 0) {
				self::treeSelector($v);
			}			
		}
		$parent_row["children"] = $list;
	}*/

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
		
		foreach($list as $item) {
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
			parent::where("id = " . $id)->update($data);
		}
		else {
			unset($data["id"]);
			parent::create($data);
		}
		return true;
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
		
		foreach($data as &$v) {
			$child_count = parent::where("parent_id = " . $v["id"])->count();
			if($child_count > 0)
				$v["children"] = self::listById($v["id"]);
			
		}
		return $data;
	}
	
}
