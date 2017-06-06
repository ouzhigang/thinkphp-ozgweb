<?php
namespace app\common\model;

class ArtSingle extends Base {
    
	public static function findById($id) {
		$data = parent::where("id", $id)->find();
		if($data)
			return $data->toArray();
		return NULL;
	}
	
	public static function saveData($data, $id = 0) {
		
		parent::where("id", $id)->update($data);
		
		return true;
	}
	
}
