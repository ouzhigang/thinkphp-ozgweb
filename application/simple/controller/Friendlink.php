<?php
namespace app\simple\controller;

class Friendlink extends Base {
	
	public function getlist() {
		
		$page = I("request.page", 1, "intval");
		$page_size = I("request.page_size", C("web_page_size"), "intval");
		$res_data = D("Friendlink")->getList($page, $page_size);
		$this->assign("data", $res_data);
			
		$this->display();
	}
	
	public function add() {
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("Friendlink")->where("id = " . $id)->find();			
		}
		else {
			$row = array(
				"id" => 0,
				"name" => "",
				"url" => "",
				"picture" => "",
				"sort" => 0,
				"is_picture" => 0
			);			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["url"] = I("post.url", "", "str_filter");	
			$row["picture"] = I("post.picture", "", "str_filter");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["is_picture"] = I("post.is_picture", 0, "intval");
			
			if(!$row["name"])
				$this->resFail(1, "名称不能为空");
			elseif(!$row["url"])
				$this->resFail(1, "URL不能为空");
			
			if($id != 0) {
				
				D("Friendlink")->save($row);
				$this->resSuccess("更新成功");
			}
			else {
				unset($row["id"]);
				D("Friendlink")->add($row);
				$this->resSuccess("添加成功");
			}
		}
		
		$this->assign("row", $row);
		$this->display();
	}
	
	public function del() {
		$id = I("request.id", 0, "intval");
		D("Friendlink")->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
}
