<?php
namespace app\common\model;

class Data extends Base {
    
	public function getList($page, $page_size, $type) {
		$total = $this->field("count(id) as total")->where("type = " . $type)->find();
		$total = $total["total"];
		
		$page_count = page_count($total, $page_size);
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;
		
		$list = $this
			->field("dc.name as dc_name, " . C("database.prefix") . "data.*")
			->join(C("database.prefix") . "data_class as dc on dc.id = " . C("database.prefix") . "data.dataclass_id")
			->where(C("database.prefix") . "data.type = " . $type)
			->order(C("database.prefix") . "data.sort desc, " . C("database.prefix") . "data.id desc")
			->limit($offset . ", " . $limit)
			->select();
		
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
	
	public function findById($id) {
		
	}
	
	public function saveData($data, $id = 0) {
		if($id) {
			$this->where([
				"id" => $id
			])->save($data);
		}
		else {
			unset($data["id"]);
			$data["add_time"] = time();
			$this->add($data);
		}
		return true;
	}
	
	public function delById($id = 0) {
		$this->where("id = " . $id)->delete();
		return true;
	}
	
}
