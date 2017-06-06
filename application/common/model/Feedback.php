<?php
namespace app\common\model;

class Feedback extends Base {
    
	public static function getList($page, $page_size) {
		
		$total = parent::count();
		
		$page_count = page_count($total, $page_size);
		
		$list = parent::all(function($query) use($page, $page_size) {
			$query->order([ "id" => "desc" ])->page($page, $page_size);
		});
		
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
		parent::where("id = " . $id)->delete();
		return true;
	}
	
}
