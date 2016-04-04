<?php
namespace app\simple\controller;

class DataClass extends Base {
	
	public function getlist() {
		$type = I("request.type", 0, "intval");
		
		if(I("request.get_data", 0, "intval")) {			
			$list = D("DataClass")->where("type = " . $type . " and parent_id = 0")->order("sort desc, id desc")->select();
			foreach($list as &$v) {
				$child_count = D("DataClass")->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
				$child_count = $child_count["total"];
				if($child_count > 0)
					$v["children"] = D("DataClass")->listById($v["id"]);
				
			}
			$this->resSuccess("请求成功", $list);
		}
				
		$this->display();
	}
	
	public function add() {
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("DataClass")->where("id = " . $id)->find();			
		}
		else {
			$row = array(
				"id" => 0,
				"name" => "",
				"sort" => 0,
				"parent_id" => 0,
				"type" => I("request.type", 0, "intval")
			);
			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["parent_id"] = I("post.parent_id", 0, "intval");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["type"] = I("post.type", 0, "intval");
			
			if($id != 0) {
				
				if($id == $row["parent_id"])
					$this->resFail(1, "父级分类不能为当前选中分类");
				
				D("DataClass")->save($row);
				$this->resSuccess("更新成功");
			}
			else {
				unset($row["id"]);
				D("DataClass")->add($row);
				$this->resSuccess("添加成功");
			}
		}
		
		$this->assign("row", $row);
		$this->display();
	}
	
	public function gettree() {
		$type = I("get.type", 0, "intval");
		$data = D("DataClass")->getTreeSelector($type);
		
		$this->resSuccess("请求成功", $data);
	}
	
	public function del() {
		
		$id = I("request.id", 0, "intval");
		
		$dataclass = D("DataClass")->where("id = " . $id)->find();
		$child_count = D("DataClass")->field("count(id) as total")->where("parent_id = " . $dataclass["id"])->find();
		$child_count = $child_count["total"];
		
		if($child_count > 0)
			D("DataClass")->deleteById($dataclass["id"]);
		
		//删除该分类下面的对应数据
		D("Data")->where("dataclass_id = " . $dataclass["id"])->delete();
		D("DataClass")->where("id = " . $dataclass["id"])->delete();
		$this->resSuccess("删除成功");
	}
	
}
