<?php
namespace app\mgr\controller;

class Data extends Base {
	
	public function show() {
		
		$page = input("param.page/d", 1);
		$page_size = input("param.page_size/d", config("web_page_size"));
		//$page_size = 2;
		$type = input("param.type/d", 1);
		$k_name = input("param.k_name/s", NULL);
		$k_data_class_id = input("param.k_data_class_id/d", 0);
		
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
		$id = input("param.id/d", 0);
		$data = \app\common\model\Data::findById($id);
		$data["content"] = html_entity_decode($data["content"]);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$id = input("param.id/d", 0);
			
		$row = [
			"name" => input("post.name/s", ""),
			"content" => input("post.content/s", ""),
			"data_class_id" => input("post.data_class_id/d", 0),
			"sort" => input("post.sort/d", 0),
			"type" => input("post.type/d", 0),		
			"picture" => input("post.picture/a", []),
		];
		
		//var_dump($row["picture"]);exit();
		if(count($row["picture"]) > 0) {
			$row["picture"] = json_encode($row["picture"]);
		}
		else { 
			$row["picture"] = "[]";
		}
		//var_dump($row["picture"]);exit();
		
		$row["is_index_show"] = input("post.is_index_show/d", 0);		
		$row["is_index_top"] = input("post.is_index_top/d", 0);		
		$row["recommend"] = input("post.recommend/d", 0);
				
		if($id != 0) {				
			return json(\app\common\model\Data::saveData($row, $id));
		}
		else {				
			return json(\app\common\model\Data::saveData($row));
		}
	}
	
	public function del() {
		$ids = input("param.ids", "");
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
