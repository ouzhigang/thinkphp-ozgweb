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
		
		$r = [
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => intval($total),
			"page" => $page,
			"list" => $list,
		];		
		return $r;
	}
	
	public function saveData($data, $id = 0) {
		if($id) {
			$this->where("id = " . $id)->save($data);
		}
		else {
			unset($data["id"]);
			$this->add($data);
		}
		return true;
	}
	
	public function findById($id = 0) {
		$data = $this->where("id = " . $id)->find();
		return $data;
	}
	
	public function delById($id = 0) {
		$this->where("id = " . $id)->delete();
		return true;
	}
	
}
