<?php
namespace app\common\model;

class ArtSingle extends Base {
    
	public function findById($id) {
		$data = $this->where("id = " . $id)->find();
		return $data;
	}
	
	public function saveData($data, $id = 0) {
		
		$this->where([
			"id" => $id
		])->save($data);
		
		return true;
	}
	
}
