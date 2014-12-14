<?php
namespace Simple\Controller;
use Think\Controller;

class BaseController extends Controller {

	protected function res($res_code, $desc, $data = NULL) {
		$res_data = array(
			"code" => $res_code,
			"desc" => $desc,
		);
		
		if($data)
			$res_data["data"] = $data;
		
		$this->ajaxReturn($res_data, "JSON");
		exit();
	}
	
	protected function resSuccess($desc, $data = NULL) {
		$this->res(0, $desc, $data);
	}
	
	protected function resFail($res_code, $desc, $data = NULL) {
		$this->res($res_code, $desc, $data);
	}
	
}
