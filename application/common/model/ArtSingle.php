<?php
namespace app\common\model;

class ArtSingle extends Base {
    
	public static function findById($id) {
		$data = parent::where("id", $id)->find();
		return $data->toArray();
	}
	
	public static function saveData($data, $id = 0) {
		
		parent::where("id", $id)->update($data);
		
		return true;
	}
	
}
