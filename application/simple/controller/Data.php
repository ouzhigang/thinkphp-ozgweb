<?php
namespace app\simple\controller;

class Data extends Base {
	
	public function getlist() {
		
		$get_data = input("request.get_data", NULL);
		if($get_data) {
			$page = input("request.page", 1, "intval");
			$page_size = input("request.page_size", config("web_page_size"), "intval");
			$type = input("request.type", 1, "intval");
			$res_data = \app\common\model\Data::getList($page, $page_size, $type);
			
			$r = [
				"code" => 0,
				"desc" => "请求成功",
				"data" => $res_data
			];
			\think\Response::type("json");
			return $r;
		}
		
		return $this->fetch("getlist");
	}
	
	public function add() {
				
		$id = input("request.id", 0, "intval");
				
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
				"type" => input("request.type", 0, "intval"),
				"picture" => ""
			];			
		}
		
		if(IS_POST) {

			$row = [];
			$row["name"] = input("post.name", "", "str_filter");
			$row["content"] = input("post.content", "", "str_filter");	
			$row["data_class_id"] = input("post.data_class_id", 0, "intval");
			$row["sort"] = input("post.sort", 0, "intval");
			$row["type"] = input("post.type", 0, "intval");		
			$row["picture"] = "";
			
			if(!$row["name"]) {
				$r = [
					"code" => 1,
					"desc" => "名称不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			elseif(!$row["content"]) {
				$r = [
					"code" => 1,
					"desc" => "内容不能为空"
				];
				\think\Response::type("json");
				return $r;
			}
			
			if($id != 0) {				
				\app\common\model\Data::saveData($row, $id);
				$r = [
					"code" => 0,
					"desc" => "更新成功"
				];
				\think\Response::type("json");
				return $r;
			}
			else {				
				\app\common\model\Data::saveData($row);
				$r = [
					"code" => 0,
					"desc" => "添加成功"
				];
				\think\Response::type("json");
				return $r;
			}
		}
		
		$this->assign("row", $row);
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("request.id", 0, "intval");
		\app\common\model\Data::delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
	public function upload() {
		
		$file = $_FILES["file_upload"];
		if($file && $file["size"] > 0) {
			$max_size = 1024 * 1024 * 10;
			$allow_ext_name = [
				"jpg",
				"jpeg",
				"png",
				"gif",
			];			
			
			if($file["size"] <= $max_size) {
				$ext_name = substr(strrchr($file["name"], '.'), 1);
				
				if(in_array($ext_name, $allow_ext_name)) {
					
					$filepath = md5_file($file["tmp_name"]);
					$filepath = "upload/" . $filepath . "." . $ext_name;
					move_uploaded_file($file["tmp_name"], dirname(__FILE__) . "/../../../public/static/" . $filepath);
					
					$r = [
						"code" => 0,
						"desc" => "上传完成",
						"filepath" => $filepath
					];
					\think\Response::type("json");
					return $r;
				}
				else {
					$r = [
						"code" => 1,
						"desc" => "不允许上传此类文件"
					];
					\think\Response::type("json");
					return $r;
				}
			}
			else {
				$r = [
					"code" => 1,
					"desc" => "不能上传超过10M的文件"
				];
				\think\Response::type("json");
				return $r;
			}			
		}
		else {
			$r = [
				"code" => 1,
				"desc" => "没有选择上传文件"
			];
			\think\Response::type("json");
			return $r;
		}
		
	}
	
}
