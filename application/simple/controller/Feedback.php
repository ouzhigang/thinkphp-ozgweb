<?php
namespace app\simple\controller;

use \think\Response;

class Feedback extends Base {
	
	public function getlist() {
		$get_data = input("request.get_data", NULL);
		if($get_data) {
			$page = input("request.page", 1, "intval");
			$page_size = input("request.page_size", config("web_page_size"), "intval");
			$res_data = \app\common\model\Feedback::getList($page, $page_size);
			
			return res_result($res_data, 0, "请求成功");
		}
		return $this->fetch("getlist");
	}
	
	public function del() {
		$id = input("request.id", 0, "intval");
		\app\common\model\Feedback::delById($id);
		return res_result(NULL, 0, "删除成功");
	}
	
}
