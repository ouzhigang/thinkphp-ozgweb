<?php
namespace Simple\Controller;
use Think\Controller;

class BaseController extends Controller {

	public function _initialize() {
		
		$curr_action = strtolower(__ACTION__);
		
		//忽略
		$ignore_arr = array(
			"/admin/getvcode"
		);		
		foreach($ignore_arr as $v) {
			if(strpos($curr_action, $v) !== false)
				return;
		}
		
		//检查是否已登录
		
		if(!cookie("curr_user_name")) {
			if(!session('?user')) {
				if(strpos($curr_action, "/admin/login") === false) {
					//没有登录
					
					header(strtolower("location: " . __ROOT__ . "/" . MODULE_NAME . "/admin/login"));
				}
			}
			else {
				//如果是已登录状态，停留在登录页面的话，就跳到后台首页
				if(strpos($curr_action, "/admin/login") !== false) {
					header(strtolower("location: " . __ROOT__ . "/" . MODULE_NAME . "/admin/main"));
				}
			}
		}
		else {
			$curr_user_name = \Common\Encrypt::decode(cookie("curr_user_name"));
			
			$where = array(
				"name" => $curr_user_name
			);
			$user =  D("User")->where($where)->find();
			session("user", $user);
		}
		
		//公用部分
		$this->assign("admin_path", dirname(__APP__) . "/" . strtolower(MODULE_NAME) . "/" . strtolower(CONTROLLER_NAME));
	}
	
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
