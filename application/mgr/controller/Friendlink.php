<?php
namespace app\mgr\controller;

class Friendlink extends Base {
	
	public function show() {
		
		$page = input("param.page/d", 1);
		$page_size = input("param.page_size/d", config("web_page_size"));
			
		$data = \app\common\model\Friendlink::getList($page, $page_size);
		
		$this->assign("data", $data);
		
		//分页导航
		$page_first = config("web_root") . "mgr/friendlink/show?page=1";
		$page_prev = config("web_root") . "mgr/friendlink/show?page=" . ($page <= 1 ? 1 : $page - 1);
		$page_next = config("web_root") . "mgr/friendlink/show?page=" . ($page >= $data["page_count"] ? $data["page_count"] : $page + 1);
		$page_last = config("web_root") . "mgr/friendlink/show?page=" . $data["page_count"];
		
		$this->assign("page_first", $page_first);
		$this->assign("page_prev", $page_prev);
		$this->assign("page_next", $page_next);
		$this->assign("page_last", $page_last);		
		//分页导航 end
		
		return $this->fetch("show");
	}
	
	public function get() {
		$id = input("param.id/d", 0);
		$data = \app\common\model\Friendlink::findById($id);
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function add() {
		$id = input("param.id/d", 0);
		
		if(request()->isPOST()) {
			$row = [
				"name" => input("post.name/s", ""),
				"url" => input("post.url/s", ""),
				"picture" => input("post.picture/s", ""),
				"sort" => input("post.sort/d", 0),
				"is_picture" => input("post.is_picture/d", 0),
			];
			
			if($id != 0) {
				$res = \app\common\model\Friendlink::saveData($row, $id);
				return json($res);
			}
			else {
				$res = \app\common\model\Friendlink::saveData($row);
				return json($res);
			}
		}
		return $this->fetch("add");
	}
	
	public function del() {
		$id = input("param.id/d", 0);
		\app\common\model\Friendlink::delById($id);
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
