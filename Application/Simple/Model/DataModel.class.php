<?php
namespace Simple\Model;
use Think\Model;

class DataModel extends Model {
    
	static function getList($page, $page_size, $type) {
		$m = M("Data");
		$total = $m->field("count(id) as total")->where("type = " . $type)->find();
		$total = $total["total"];
		
		$page_count = page_count($total, $page_size);
		$offset = ($page - 1) * $page_size;
		$limit = $offset + $page_size;
		
		$list = $m
			->field("dc.name as dc_name, " . C("DB_PREFIX") . "data.*")
			->join(C("DB_PREFIX") . "data_class as dc on dc.id = " . C("DB_PREFIX") . "data.dataclass_id")
			->where(C("DB_PREFIX") . "data.type = " . $type)
			->order(C("DB_PREFIX") . "data.sort desc, " . C("DB_PREFIX") . "data.id desc")
			->limit($offset . ", " . $limit)
			->select();
		
		foreach($list as &$v) {
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
		}
		
		return array(
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => $total,
			"page" => $page,
			"list" => $list,
		);
	}
	
}
