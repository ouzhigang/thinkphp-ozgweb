<?php
namespace app\mgr\controller;

class DataCat extends \app\BaseController {
	
	protected $middleware = [ '\app\middleware\UserCheck' ];
	
	public function show() {
		$type = input("param.type/d", 0);
		
		$data = \app\common\model\DataCat::getList($type);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function get() {
		$id = input("param.id/d", 0);
		$data = \app\common\model\DataCat::getById($id);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$id = input("param.id/d", 0);
			
		$row = [
			"name" => input("post.name/s", ""),
			"parent_id" => input("post.parent_id/d", 0),
			"sort" => input("post.sort/d", 0),
			"type" => input("post.type/d", 0),
		];
			
		if($id != 0) {				
			return json(\app\common\model\DataCat::saveData($row, $id));
		}
		else {
			return json(\app\common\model\DataCat::saveData($row));
		}
	}
	
	public function del() {
		
		$id = input("param.id/d", 0);
		\app\common\model\DataCat::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
