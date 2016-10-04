<?php
namespace app\simple\controller;

use \think\Response;
use \think\Request;

class Data extends Base {
	
	public function getlist() {
		
		$get_data = input("param.get_data", NULL);
		if($get_data) {
			$page = input("param.page", 1, "intval");
			$page_size = input("param.page_size", config("web_page_size"), "intval");
			$type = input("param.type", 1, "intval");
			$res_data = \app\common\model\Data::getList($page, $page_size, $type);
						
			return json(res_result($res_data, 0, "请求成功"));
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
				
		$id = input("param.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = \app\common\model\Data::findById($id);			
		}
		else {
			$row = [
				"id" => 0,
				"name" => "",
				"content" => "",
				"data_class_id" => 0,
				"sort" => 0,
				"type" => input("param.type", 0, "intval"),
				"picture" => [ "" ],
			];			
		}
		
		if(Request::instance()->isPOST()) {

			$row = [];
			$row["name"] = input("post.name", "", "str_filter");
			$row["content"] = input("post.content", "", "str_filter");	
			$row["data_class_id"] = input("post.data_class_id", 0, "intval");
			$row["sort"] = input("post.sort", 0, "intval");
			$row["type"] = input("post.type", 0, "intval");		
			$row["picture"] = implode(",", input("post.picture/a"));
			
			if($id != 0) {				
				return json(\app\common\model\Data::saveData($row, $id));
			}
			else {				
				return json(\app\common\model\Data::saveData($row));
			}
		}
		
		$this->assign("row", $row);
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("param.id", 0, "intval");
		\app\common\model\Data::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
	public function upload() {
		
		$file = NULL;
		foreach(request()->file() as $f) {
			$file = $f;
			break;
		}
		
		if($file && $file->getSize() > 0) {
			$max_size = 1024 * 1024 * 10;
			$allow_ext_name = [
				"jpg",
				"jpeg",
				"png",
				"gif",
			];			
			
			if($file->getSize() <= $max_size) {
				$ext_name = substr(strrchr($file->getInfo()["name"], '.'), 1);
				
				if(in_array($ext_name, $allow_ext_name)) {
					
					$info = $file->move(ROOT_PATH . "public/static/upload");
					if($info) {
						return json(res_result([ "filepath" => str_replace("\\", "/", $info->getSaveName()) ], 0, "上传完成"));
					}
					return json(res_result(NULL, 1, $file->getError()));
				}
				else {
					return json(res_result(NULL, 1, "不允许上传此类文件"));
				}
			}
			else {
				return json(res_result(NULL, 1, "不能上传超过10M的文件"));
			}			
		}
		else {
			return json(res_result(NULL, 1, "没有选择上传文件"));
		}
		
	}
	
}
