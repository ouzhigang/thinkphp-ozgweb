<?php
namespace app\simple\controller;

class Feedback extends Base {
	
	public function getlist() {
		$get_data = I("request.get_data", NULL);
		if($get_data) {
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("web_page_size"), "intval");
			$res_data = D("Feedback")->getList($page, $page_size);
			
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
	
	public function del() {
		$id = I("request.id", 0, "intval");
		D("Feedback")->delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
}
