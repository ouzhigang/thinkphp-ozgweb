<?php
namespace app\simple\controller;

class Feedback extends Base {
	
	public function getlist() {
		$get_data = input("request.get_data", NULL);
		if($get_data) {
			$page = input("request.page", 1, "intval");
			$page_size = input("request.page_size", config("web_page_size"), "intval");
			$res_data = \app\common\model\Feedback::getList($page, $page_size);
			
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
		$id = input("request.id", 0, "intval");
		\app\common\model\Feedback::delById($id);
		$r = [
			"code" => 0,
			"desc" => "删除成功"
		];
		\think\Response::type("json");
		return $r;
	}
	
}
