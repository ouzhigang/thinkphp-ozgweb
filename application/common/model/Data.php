<?php
namespace app\common\model;

class Data extends Base {
	
	public static function getList($page, $page_size, $type) {
		$total = parent::where("type = " . $type)->count();
		
		$page_count = page_count($total, $page_size);
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;
		
		$prefix = config("database.prefix");
		$list = \think\Db::table($prefix . "data")
			->field("d.*, dc.name as dc_name")
			->alias("d")
			->join($prefix . "data_class as dc", "d.data_class_id = dc.id", "left")
			->where("d.type = " . $type)
			->order("sort desc, id desc")
			->limit($offset . ", " . $limit)
			->select();
		
		foreach($list as &$v) {
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
			$v["dc_name"] = $v["dc_name"] ? $v["dc_name"] : "[暂无]";
		}
		
		$r = [
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => intval($total),
			"page" => $page,
			"list" => $list,
		];
		
		return $r;
	}
	
	public static function findById($id) {
		$data = parent::where("id = " . $id)->find();
		return $data->toArray();
	}
	
	public static function saveData($data, $id = 0) {
		if($id) {
			parent::where("id = " . $id)->update($data);
		}
		else {
			unset($data["id"]);
			$data["add_time"] = time();
			parent::create($data);
		}
		return true;
	}
	
	public static function delById($id = 0) {
		parent::where("id = " . $id)->delete();
		return true;
	}
	
}
