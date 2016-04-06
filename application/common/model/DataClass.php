<?php
namespace app\common\model;

class DataClass extends Base {
    
	public function deleteById($id) {
		$dc_list = $this->where("parent_id = " . $id)->select();
		foreach($dc_list as $v) {
			$child_count = $this->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
			$child_count = $child_count["total"];
			if($child_count > 0)
				$this->deleteById($v["id"]);
			
			//删除该分类下面的对应数据
			D("Data")->where("dataclass_id = " . $v["id"])->delete();
			$this->where("id = " . $v["id"])->delete();
		}
		
	}
	
	public function getById($id) {
		$dataclass = $this->where("id = " . $id)->find();
		if($dataclass["parent_id"] != 0)
			$dataclass["parent"] = $this->getById($dataclass["parent_id"]);
		return $dataclass;
	}
	
	public function listById($id) {		
		$dc_list = $this->where("parent_id = " . $id)->order("sort desc, id desc")->select();
		foreach($dc_list as &$v) {
			$child_count = $this->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
			$child_count = $child_count["total"];
			if($child_count > 0)
				$v["children"] = $this->listById($v["id"]);
			
		}
		return $dc_list;
	}
	
	/*public function getTreeSelector($type) {
		$data = [];
		$list = $this->where("parent_id = 0 and type = " . $type)->order("sort desc, id desc")->select();
		
		foreach($list as &$v) {
						
			$res = $this->where("parent_id = " . $v["id"])->count();
			if($res > 0) {
				$this->treeSelector($v);
			}
			
			$data[] = $v;
		}
		return $data;
	}	
	protected function treeSelector(&$parent_row) {
		
		$list = $this->where("parent_id = " . $parent_row["id"])->order("sort desc, id desc")->select();
				
		foreach($list as &$v) {
			$res = $this->where("parent_id = " . $v["id"])->count();
			if($res > 0) {
				$this->treeSelector($v);
			}			
		}
		$parent_row["children"] = $list;
	}*/

	public function getTreeSelector($type) {
		$data = [];
		$list = $this->where("parent_id = 0 and type = " . $type)->order("sort desc, id desc")->select();
		foreach($list as $v) {
			$data[] = [
				"id" => intval($v["id"]),
				"parent_id" => 0,
				"name" => $v["name"]
			];
			$res = $this->where("parent_id = " . $v["id"])->count();
			if($res) {
				$this->treeSelector($data, $v["id"]);
			}
		}
		return $data;
	}
	
	protected function treeSelector(&$data, $parent_id) {
		$list = $this->where("parent_id = " . $parent_id)->order("sort desc, id desc")->select();
		foreach($list as $item) {
			$data[] = [
				"id" => intval($item["id"]),
				"parent_id" => intval($item["parent_id"]),
				"name" => $item["name"]
			];
			$this->treeSelector($data, $item["id"]);
		}
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
	
	public function delById($id = 0) {
		
		$dataclass = $this->where("id = " . $id)->find();
		$child_count = $this->field("count(id) as total")->where("parent_id = " . $dataclass["id"])->find();
		$child_count = $child_count["total"];
		
		if($child_count > 0)
			$this->deleteById($dataclass["id"]);
		
		//删除该分类下面的对应数据
		D("Data")->where("dataclass_id = " . $dataclass["id"])->delete();
		$this->where("id = " . $dataclass["id"])->delete();
		
		return true;
	}
	
	public function findById($id = 0) {
		$data = $this->where("id = " . $id)->find();
		return $data;
	}
	
	public function getList($type = 0) {
		$data = $this->where("type = " . $type . " and parent_id = 0")->order("sort desc, id desc")->select();
		foreach($data as &$v) {
			$child_count = $this->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
			$child_count = $child_count["total"];
			if($child_count > 0)
				$v["children"] = $this->listById($v["id"]);
			
		}
		return $data;
	}
	
}
