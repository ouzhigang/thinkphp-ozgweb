<?php
namespace app\mgr\controller;

class Other extends Base {
	
	public function serverInfo() {
		
		$data = [
			'server_name' => input("server.SERVER_NAME"),
			'os' => PHP_OS,
			'server_software' => input("server.SERVER_SOFTWARE"),
			'php_version' => PHP_VERSION,
			'upload_file_status' => get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : "<span class=\"f2\">不允许上传文件</span>",
			'max_execution_time' => get_cfg_var("max_execution_time") . "秒",
			'document_root' => input("server.DOCUMENT_ROOT"),
			'now' => date("Y-m-d H:i:s"),
			'thinkphp_ver' => THINK_VERSION,
		];
		
		return json(res_result($data, 0, "请求成功"));
    }
	
	public function logout() {
		$res = \app\common\model\User::logout();
	
		return json($res);
	}
	
}
