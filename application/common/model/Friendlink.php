<?php
namespace app\common\model;

use \think\Response;

class Friendlink extends Base {
    
	public static function getList($page, $page_size) {
		
		$total = parent::count();
		
		$page_count = page_count($total, $page_size);
		
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;
		
		$data = parent::all(function($query) use($offset, $limit) {
			$query->order([ "id" => "desc" ])->limit($offset, $limit);
		});
		
		$list = [];
		foreach($data as $v) {
			$item = $v->toArray();
			$list[] = $item;			
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
	
	public static function saveData($data, $id = 0) {
		if(!$data["name"]) {
			return Response::result(NULL, 1, "名称不能为空", "json");
		}
		elseif(!$data["url"]) {
			return Response::result(NULL, 1, "URL不能为空", "json");
		}
		
		if($id) {
			parent::where("id = " . $id)->update($data);
			
			return Response::result(NULL, 0, "更新成功", "json");
		}
		else {
			unset($data["id"]);
			parent::create($data);
			
			return Response::result(NULL, 0, "添加成功", "json");
		}
	}
	
	public static function findById($id = 0) {
		$data = parent::where("id = " . $id)->find();
		return $data->toArray();
	}
	
	public static function delById($id = 0) {
		parent::where("id = " . $id)->delete();
		return true;
	}
	
}
