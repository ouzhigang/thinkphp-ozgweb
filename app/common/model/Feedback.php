<?php
namespace app\common\model;

class Feedback extends Base {
    
	public static function getList($page, $page_size) {
		
		$total = self::count();
		
		$page_count = page_count($total, $page_size);
		
		$list = self::where(function($query) use($page, $page_size) {
			$query->order("id", "desc")->page($page, $page_size);
		})->select();
		
		foreach($list as &$v) {
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
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
	
	public static function delById($id = 0) {
		self::where("id", $id)->delete();
		return true;
	}
	
}
