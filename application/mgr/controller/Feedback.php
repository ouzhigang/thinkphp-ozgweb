<?php
namespace app\mgr\controller;

class Feedback extends Base {
	
	public function show() {
		
		$page = input("param.page/d", 1);
		$page_size = input("param.page_size/d", config("web_page_size"));
		$data = \app\common\model\Feedback::getList($page, $page_size);
		$this->assign("data", $data);
		
		//分页导航
		$page_first = config("web_root") . "mgr/feedback/show?page=1";
		$page_prev = config("web_root") . "mgr/feedback/show?page=" . ($page <= 1 ? 1 : $page - 1);
		$page_next = config("web_root") . "mgr/feedback/show?page=" . ($page >= $data["page_count"] ? $data["page_count"] : $page + 1);
		$page_last = config("web_root") . "mgr/feedback/show?page=" . $data["page_count"];
		
		$this->assign("page_first", $page_first);
		$this->assign("page_prev", $page_prev);
		$this->assign("page_next", $page_next);
		$this->assign("page_last", $page_last);		
		//分页导航 end
		
		return $this->fetch("show");
	}
	
	public function del() {
		$id = input("param.id/d", 0);
		\app\common\model\Feedback::delById($id);
		
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
