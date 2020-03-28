<?php
namespace app\common\model;

class Friendlink extends Base {
    
	public static function getList($page, $page_size) {
		
		$total = self::count();
		
		$page_count = page_count($total, $page_size);
		
		$list = self::where(function($query) use($page, $page_size) {
			$query->order("id", "desc")->page($page, $page_size);
		})->select();
		
		$r = [
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => intval($total),
			"page" => $page,
			"list" => $list,
		];		
		return $r;
	}
	
	public static function saveData($data, $id = 0) {
		
		if(!$data["name"]) {
			return res_result(NULL, 1, "名称不能为空");
		}
		elseif(!$data["url"]) {
			return res_result(NULL, 1, "URL不能为空");
		}
		
		if($id) {
			self::where("id", $id)->update($data);
			return res_result(NULL, 0, "修改成功");
		}
		else {
			unset($data["id"]);
			self::create($data);
			return res_result(NULL, 0, "添加成功");
		}
	}
	
	public static function findById($id = 0) {
		$data = self::where("id", $id)->find();
		return $data->toArray();
	}
	
	public static function delById($id = 0) {
		self::where("id", $id)->delete();
		return true;
	}
	
}
