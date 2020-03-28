<?php
namespace app\common\model;

class ArtSingle extends Base {
    
	public static function findById($id) {
		$data = self::where("id", $id)->find();
		if($data) {
			return $data->toArray();
		}
		return NULL;
	}
	
	public static function saveData($data, $id = 0) {
		
		self::where("id", $id)->update($data);
		
		return true;
	}
	
}
