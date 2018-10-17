<?php
namespace app\mgr\controller;

class Data extends Base {
	
	public function show() {
		
		$page = input("param.page", 1, "intval");
		$page_size = input("param.page_size", config("web_page_size"), "intval");
		//$page_size = 2;
		$type = input("param.type", 1, "intval");
		$k_name = input("param.k_name", NULL, "str_filter");
		$k_data_class_id = input("param.k_data_class_id", 0, "intval");
		
		$wq = "1 = 1";
		if(!is_null($k_name)) {
			$wq .= " and d.name like '%" . $k_name . "%'";
		}
		if($k_data_class_id != 0) {
			$wq .= " and d.data_class_id = " . $k_data_class_id;
		}
		$data = \app\common\model\Data::getList($page, $page_size, $type, $wq);
		
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function get() {
		$id = input("param.id", 0, "intval");
		$data = \app\common\model\Data::findById($id);
		$data["content"] = html_entity_decode($data["content"]);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$id = input("param.id", 0, "intval");
			
		$row = [];
		$row["name"] = input("post.name", "", "str_filter");
		$row["content"] = input("post.content", "", "str_filter");	
		$row["data_class_id"] = input("post.data_class_id", 0, "intval");
		$row["sort"] = input("post.sort", 0, "intval");
		$row["type"] = input("post.type", 0, "intval");		
		$row["picture"] = input("post.picture/a");
		
		//var_dump($row["picture"]);exit();
		if(count($row["picture"]) > 0) {
			$row["picture"] = json_encode($row["picture"]);
		}
		else { 
			$row["picture"] = "[]";
		}
		//var_dump($row["picture"]);exit();
		
		$row["is_index_show"] = input("post.is_index_show", 0, "intval");		
		$row["is_index_top"] = input("post.is_index_top", 0, "intval");		
		$row["recommend"] = input("post.recommend", 0, "intval");
				
		if($id != 0) {				
			return json(\app\common\model\Data::saveData($row, $id));
		}
		else {				
			return json(\app\common\model\Data::saveData($row));
		}
	}
	
	public function del() {
		$ids = input("param.ids", "", "str_filter");
		$ids = explode(",", $ids);
		$res = \app\common\model\Data::delById($ids);
		return json($res);
	}
	
	public function upload() {
		
		$file = NULL;
		foreach(request()->file() as $f) {
			$file = $f;
			break;
		}
		
		if($file && $file->getSize() > 0) {
			$max_size = config("max_upload");
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
				return json(res_result(NULL, 1, "不能上传超过" . ($max_size / 1024 / 1024) . "M的文件"));
			}			
		}
		else {
			return json(res_result(NULL, 1, "没有选择上传文件"));
		}
		
	}
	
}
