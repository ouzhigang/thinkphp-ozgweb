<?php
namespace app\simple\controller;

class Other extends Base {
	
	public function main() {
		
		$this->assign('server_name', I("server.SERVER_NAME"));
		$this->assign('os', PHP_OS);
		$this->assign('server_software', I("server.SERVER_SOFTWARE"));
		$this->assign('php_version', PHP_VERSION);
		$this->assign('upload_file_status', get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : "<span class=\"f2\">不允许上传文件</span>");
		$this->assign('max_execution_time', get_cfg_var("max_execution_time"));
		$this->assign('document_root', I("server.DOCUMENT_ROOT"));
		$this->assign('now', date("Y-m-d H:i:s"));
		$this->assign("thinkphp_ver", THINK_VERSION);
		
		return $this->fetch("main");
    }
	
	public function logout() {		
		D("User")->logout();
		
		header(strtolower("location:login"));
	}
	
}
