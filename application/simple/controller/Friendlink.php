<?php
namespace app\simple\controller;

use \think\Response;
use \think\Request;

class Friendlink extends Base {
	
	public function getlist() {
		
		$page = input("param.page", 1, "intval");
		$page_size = input("param.page_size", config("web_page_size"), "intval");
		$res_data = \app\common\model\Friendlink::getList($page, $page_size);
		$this->assign("data", $res_data);
			
		return $this->fetch("getlist");
	}
	
	public function add() {
		$id = input("param.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = \app\common\model\Friendlink::findById($id);			
		}
		else {
			$row = [
				"id" => 0,
				"name" => "",
				"url" => "",
				"picture" => "",
				"sort" => 0,
				"is_picture" => 0
			];			
		}
		
		if(Request::instance()->isPOST()) {
			$row = [];
			$row["name"] = input("post.name", "", "str_filter");
			$row["url"] = input("post.url", "", "str_filter");	
			$row["picture"] = input("post.picture", "", "str_filter");
			$row["sort"] = input("post.sort", 0, "intval");
			$row["is_picture"] = input("post.is_picture", 0, "intval");
			
			if($id != 0) {				
				return json(\app\common\model\Friendlink::saveData($row, $id));
			}
			else {
				return json(\app\common\model\Friendlink::saveData($row));
			}
		}
		
		$this->assign("row", $row);
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("param.id", 0, "intval");
		\app\common\model\Friendlink::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
