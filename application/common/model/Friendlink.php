<?php
namespace app\common\model;

class Friendlink extends Base {
    
	public function getList($page, $page_size) {
		
		$total = $this->field("count(id) as total")->find();
		$total = $total["total"];
		
		$page_count = page_count($total, $page_size);
		
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;

		$list = $this->order("id desc")->limit($offset . ", " . $limit)->select();
		
		$r = array(
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => intval($total),
			"page" => $page,
			"list" => $list,
		);		
		return $r;
	}
	
}
