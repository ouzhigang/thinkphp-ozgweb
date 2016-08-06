<?php
namespace app\common\model;

use \think\Response;

class Feedback extends Base {
    
	public static function getList($page, $page_size) {
		
		$total = parent::count();
		
		$page_count = page_count($total, $page_size);
		
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;
		
		$data = parent::all(function($query) use($offset, $limit) {
			$query->order([ "id" => "desc" ])->limit($offset, $limit);
		});
		
		$list = [];
		foreach($data as $v) {
			$item = $v->toArray();
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
			$list[] = $item;
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
