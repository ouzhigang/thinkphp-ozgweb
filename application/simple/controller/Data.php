<?php
namespace app\simple\controller;

class Data extends Base {
	
	public function getlist() {
		
		$get_data = I("request.get_data", NULL);
		if($get_data) {
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("web_page_size"), "intval");
			$type = I("request.type", 1, "intval");
			$res_data = D("Data")->getList($page, $page_size, $type);
			
			$this->resSuccess("请求成功", $res_data);
		}
		
		$this->display();
	}
	
	public function add() {
				
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("Data")->where("id = " . $id)->find();			
		}
		else {
			$row = array(
				"id" => 0,
				"name" => "",
				"content" => "",
				"dataclass_id" => 0,
				"sort" => 0,
				"type" => I("request.type", 0, "intval"),
				"picture" => ""
			);			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["content"] = I("post.content", "", "str_filter");	
			$row["dataclass_id"] = I("post.dataclass_id", 0, "intval");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["type"] = I("post.type", 0, "intval");		
			$row["picture"] = "";
			
			if(!$row["name"])
				$this->resFail(1, "名称不能为空");
			elseif(!$row["content"])
				$this->resFail(1, "内容不能为空");
			
			if($id != 0) {
				
				D("Data")->save($row);
				$this->resSuccess("更新成功");
			}
			else {
				unset($row["id"]);
				$row["add_time"] = time();
				D("Data")->add($row);
				$this->resSuccess("添加成功");
			}
		}
		
		$this->assign("row", $row);		
		$this->display();
	}
	
	public function del() {
		$id = I("request.id", 0, "intval");
		D("Data")->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
}
