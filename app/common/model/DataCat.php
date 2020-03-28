<?php
namespace app\common\model;

class DataCat extends Base {
    
	public static function deleteById($id) {
		$dc_list = self::where("parent_id", $id)->select();
		foreach($dc_list as $v) {
			$child_count = self::where("parent_id", $v["id"])->count();
			if($child_count > 0) {
				self::deleteById($v["id"]);
			}
			//删除该分类下面的对应数据
			Data::where("data_cat_id", $v["id"])->delete();
			self::where("id", $v["id"])->delete();
		}
		
	}
	
	public static function getById($id) {
		$dataclass = self::where("id", $id)->find();
		$dataclass = $dataclass->toArray();
		if($dataclass["parent_id"] != 0)
			$dataclass["parent"] = self::getById($dataclass["parent_id"]);
		return $dataclass;
	}
	
	public static function listById($id) {		
		$dc_list = self::where(function($query) use($id) {
			$query->where("parent_id", $id)
				->order("sort", "desc")
				->order("id", "desc");
		})->select();
		
		$list = [];
		foreach($dc_list as $v) {
			$item = $v->toArray();
			$item["children"] = [];
			
			$child_count = self::where("parent_id", $item["id"])->count();
			if($child_count > 0) {
				$item["children"] = self::listById($item["id"]);
			}
			
			$list[] = $item;
		}
		return $list;
	}

	public static function saveData($data, $id = 0) {
		if($id) {
			if($id == $data["parent_id"]) {
				return res_result(NULL, 1, "父级分类不能为当前选中分类");
			}
			
			self::where("id", $id)->update($data);
			
			return res_result(NULL, 0, "更新成功");
		}
		else {
			unset($data["id"]);
			self::create($data);
			
			return res_result(NULL, 0, "添加成功");
		}
	}
	
	public static function delById($id = 0) {
		
		$dataclass = self::where("id", $id)->find();
		$dataclass = $dataclass->toArray();
		$child_count = self::where("parent_id", $dataclass["id"])->count();
		
		if($child_count > 0)
			self::deleteById($dataclass["id"]);
		
		//删除该分类下面的对应数据
		Data::where("data_cat_id", $dataclass["id"])->delete();
		self::where("id", $dataclass["id"])->delete();
		
		return true;
	}
	
	public static function findById($id = 0) {
		$data = self::where("id", $id)->find();
		return $data->toArray();
	}
	
	public static function getList($type = 0) {
		$data = self::where(function($query) use($type) {
			$query->where("type", $type)
					->where("parent_id", 0)
					->order("sort", "desc")
					->order("id", "desc");
		})->select();
		
		$list = [];		
		foreach($data as $v) {
			$item = $v->toArray();
			$item["children"] = [];
			
			$child_count = self::where("parent_id", $item["id"])->count();
			if($child_count > 0)
				$item["children"] = self::listById($item["id"]);
			
			$list[] = $item;
		}
		return $list;
	}
	
}
