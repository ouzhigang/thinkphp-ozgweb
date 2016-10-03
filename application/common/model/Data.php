<?php
namespace app\common\model;

use \think\Response;

class Data extends Base {
	
	public static function getList($page, $page_size, $type) {
		$total = parent::where("type = " . $type)->count();
		
		$page_count = page_count($total, $page_size);
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;
		
		$prefix = config("database.prefix");
		$data = parent::where("d.type = " . $type)
			->field("d.*, dc.name as dc_name")
			->alias("d")
			->join($prefix . "data_class as dc", "d.data_class_id = dc.id", "left")
			->order("sort desc, id desc")
			->limit($offset . ", " . $limit)
			->select();
			
		$list = [];
		foreach($data as $v) {
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
			$v["dc_name"] = $v["dc_name"] ? $v["dc_name"] : "[暂无]";
			
			$v["picture"] = explode(",", $v["picture"]);
			
			$list[] = $v;
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
		$data["picture"] = explode(",", $data["picture"]);	
		return $data->toArray();
	}
	
	public static function saveData($data, $id = 0) {
		
		if(!$data["name"]) {
			return res_result(NULL, 1, "名称不能为空");
			
		}
		elseif(!$data["content"]) {
			return res_result(NULL, 1, "内容不能为空");
		}
		
		if($id) {
			parent::where("id = " . $id)->update($data);
			
			return res_result(NULL, 0, "更新成功");
		}
		else {
			unset($data["id"]);
			$data["add_time"] = time();
			parent::create($data);
			
			return res_result(NULL, 0, "添加成功");
		}
		
	}
	
	public static function delById($id = 0) {
		parent::where("id = " . $id)->delete();
		return true;
	}
	
}
