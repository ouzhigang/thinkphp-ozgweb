<?php
namespace Simple\Model;
use Think\Model;

class AdminModel extends Model {
    
	static function getList($page, $page_size) {
		$m = M("Admin");
		
		$total = $m->field("count(id) as total")->find();
		$total = $total["total"];
		
		$page_count = page_count($total, $page_size);
		
		$offset = ($page - 1) * $page_size;
		$limit = $offset + $page_size;

		$list = $m->order("id desc")->limit($offset . ", " . $limit)->select();
		foreach($list as &$v)
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
		
		return array(
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => intval($total),
			"page" => $page,
			"list" => $list,
		);
	}
	
}
