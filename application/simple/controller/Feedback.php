<?php
namespace app\simple\controller;

class Feedback extends Base {
	
	public function getlist() {
		$get_data = I("request.get_data", NULL);
		if($get_data) {
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("web_page_size"), "intval");
			$res_data = D("Feedback")->getList($page, $page_size);
			
			$this->resSuccess("请求成功", $res_data);
		}
		$this->display();
	}
	
	public function del() {
		$id = I("request.id", 0, "intval");
		D("Feedback")->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
}
