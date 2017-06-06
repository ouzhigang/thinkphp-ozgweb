<?php
namespace app\mgr\controller;

class Feedback extends Base {
	
	public function show() {
		$get_data = input("param.get_data", NULL);
		if($get_data) {
			$page = input("param.page", 1, "intval");
			$page_size = input("param.page_size", config("web_page_size"), "intval");
			$data = \app\common\model\Feedback::getList($page, $page_size);
			
			return json(res_result($data, 0, "请求成功"));
		}
		return $this->fetch("show");
	}
	
	public function del() {
		$id = input("param.id", 0, "intval");
		\app\common\model\Feedback::delById($id);
		
		return json(res_result(NULL, 0, "删除成功"));
	}
	
}
