<?php
namespace Simple\Controller;
use Simple\Controller\BaseController;
use Simple\Model\AdminModel;
use Simple\Model\ArtSingleModel;
use Simple\Model\DataClassModel;
use Simple\Model\DataModel;

class AdminController extends BaseController {
	
    public function login() {
		
		if(cookie("curr_user_name")) {
			//一周内自动登录		
			$name = str_filter(cookie("curr_user_name"));			
			$name = \Common\Encrypt::decode($name);
			
			$where = array(
				"name" => $name,
				"is_admin" => 1
			);
			$user =  D("User")->where($where)->find();					
			unset($user["pwd"]);
			session("user", $user);
			$user["err_login"] = 0;
		
			D("User")->where(array(
				"id" => $user["id"]
			))->save($user);
		
			header("location:main");
			exit();
		}
		
		if(IS_POST) {
			$name = I("post.name", NULL, "str_filter");
			$pwd = I("post.pwd", NULL, "str_filter");		
			
			//提交登录
			$remember = I("post.remember", 0, "intval");
			$vcode = I("post.vcode", "", "str_filter");			
			$this->ajaxReturn(D("User")->adminLogin($name, $pwd, $remember, $vcode), "JSON");
		}
		
        $this->display();
    }
	
	public function getvcode() {
		$verify = new \Think\Verify();
		$verify->fontSize = 14;
		$verify->length = 4;
		$verify->useNoise = false;
		$verify->codeSet = '0123456789';
		$verify->imageW = 120;
		$verify->imageH = 30;
		//$verify->expire = 600;
		$verify->entry(1);		
	}
	
	public function main() {
		
		$this->assign('server_name', $_SERVER["SERVER_NAME"]);
		$this->assign('os', PHP_OS);
		$this->assign('server_software', $_SERVER["SERVER_SOFTWARE"]);
		$this->assign('php_version', PHP_VERSION);
		$this->assign('upload_file_status', get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : "<span class=\"f2\">不允许上传文件</span>");
		$this->assign('max_execution_time', get_cfg_var("max_execution_time"));
		$this->assign('document_root', $_SERVER["DOCUMENT_ROOT"]);
		$this->assign('now', date("Y-m-d H:i:s"));
		$this->assign("thinkphp_ver", THINK_VERSION);
		
		$this->display();
    }
	
	public function logout() {		
		D("User")->logout();
		
		header(strtolower("location: " . __ROOT__ . "/" . MODULE_NAME . "/admin/login"));
	}
	
	public function admin_list() {
		
		//分页索引和每页显示数
		$get_data = I("request.get_data", NULL);
		if($get_data) {			
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("WEB_PAGE_SIZE"), "intval");
			$this->resSuccess("请求成功", D("User")->getList($page, $page_size));
		}
				
		$this->display();
	}
	
	public function admin_add() {
		
		if(IS_POST) {
			$name = I("post.name", "", "str_filter");
			$pwd = I("post.pwd", "", "str_filter");
			$pwd2 = I("post.pwd2", "", "str_filter");
			if(!$name)
				$this->resFail(1, "用户名不能为空");
			if(!$pwd)
				$this->resFail(1, "密码不能为空");
			if($pwd != $pwd2)
				$this->resFail(1, "确认密码不正确");
			
			$total = D("User")->field("count(id) as total")->where("name = '" . $name . "'")->find();
			$total = $total["total"];
			if($total > 0)
				$this->resFail(1, "该用户已存在");
				
			$user = array(
				"name" => $name,
				"pwd" => $pwd,
				"add_time" => time(),
				"is_admin" => 1
			);
			D("User")->add($user);
			$this->resSuccess("添加成功");
		}
		
		$this->display();		
	}
	
	public function admin_del() {
		$id = I("request.id", 0, "intval");
		$r = D("User")->del($id);
		$this->ajaxReturn($r, "JSON");
	}
	
	public function admin_updatepwd() {
		
		if(IS_POST) {
			$curr_user = session("user");
			$old_pwd = I("post.old_pwd", "", "str_filter");
			$pwd = I("post.pwd", "", "str_filter");
			$pwd2 = I("post.pwd2", "", "str_filter");
			
			if(!$old_pwd)
				$this->resFail(1, "旧密码不能为空");
			if(!$pwd)
				$this->resFail(1, "新密码不能为空");
			if($pwd != $pwd2)
				$this->resFail(1, "确认密码不正确");
			
			$user = D("User")->where("name = '" . $curr_user["name"] . "' and pwd = '" . $old_pwd . "'")->find();
			if($user) {
				$user["pwd"] = $pwd;
				D("User")->save($user);
				$this->resSuccess("修改密码成功");
			}
			else
				$this->resFail(1, "旧密码不正确");
		}
		
		$this->display();
	}
	
	public function art_single_get() {
		$id = I("request.id", 0, "intval");
		
		$data = D("ArtSingle")->where("id = " . $id)->find();
		
		if(IS_POST) {
			$content = I("post.content", "", "str_filter");
			
			$data["content"] = $content;
			D("ArtSingle")->save($data);
			$this->resSuccess("更新成功");
		}
		
		$data["content"] = html_entity_decode($data["content"]);
		$this->assign("data", $data);
		$this->display();
	}
	
	public function dataclass_list() {
		$type = I("request.type", 0, "intval");
		
		if(I("request.get_data", 0, "intval")) {			
			$list = D("DataClass")->where("type = " . $type . " and parent_id = 0")->order("sort desc, id desc")->select();
			foreach($list as &$v) {
				$child_count = D("DataClass")->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
				$child_count = $child_count["total"];
				if($child_count > 0)
					$v["children"] = D("DataClass")->listById($v["id"]);
				
			}
			$this->resSuccess("请求成功", $list);
		}
				
		$this->display();
	}
	
	public function dataclass_add() {
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("DataClass")->where("id = " . $id)->find();			
		}
		else {
			$row = array(
				"id" => 0,
				"name" => "",
				"sort" => 0,
				"parent_id" => 0,
				"type" => I("request.type", 0, "intval")
			);
			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["parent_id"] = I("post.parent_id", 0, "intval");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["type"] = I("post.type", 0, "intval");
			
			if($id != 0) {
				
				if($id == $row["parent_id"])
					$this->resFail(1, "父级分类不能为当前选中分类");
				
				D("DataClass")->save($row);
				$this->resSuccess("更新成功");
			}
			else {
				unset($row["id"]);
				D("DataClass")->add($row);
				$this->resSuccess("添加成功");
			}
		}
		
		$this->assign("row", $row);
		$this->display();
	}
	
	public function dataclass_gettree() {
		$type = I("get.type", 0, "intval");
		$data = D("DataClass")->getTreeSelector($type);
		
		$this->resSuccess("请求成功", $data);
	}
	
	public function dataclass_del() {
		
		$id = I("request.id", 0, "intval");
		
		$dataclass = D("DataClass")->where("id = " . $id)->find();
		$child_count = D("DataClass")->field("count(id) as total")->where("parent_id = " . $dataclass["id"])->find();
		$child_count = $child_count["total"];
		
		if($child_count > 0)
			D("DataClass")->deleteById($dataclass["id"]);
		
		//删除该分类下面的对应数据
		D("Data")->where("dataclass_id = " . $dataclass["id"])->delete();
		D("DataClass")->where("id = " . $dataclass["id"])->delete();
		$this->resSuccess("删除成功");
	}
	
	public function data_list() {
		
		$get_data = I("request.get_data", NULL);
		if($get_data) {
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("WEB_PAGE_SIZE"), "intval");
			$type = I("request.type", 1, "intval");
			$res_data = D("Data")->getList($page, $page_size, $type);
			
			$this->resSuccess("请求成功", $res_data);
		}
		
		$this->display();
	}
	
	public function data_add() {
				
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("Data")->where("id = " . $id)->find();			
		}
		else {
			$row = array(
				"id" => 0,
				"name" => "",
				"content" => "",
				"dataclass_id" => 0,
				"sort" => 0,
				"type" => I("request.type", 0, "intval"),
				"picture" => ""
			);			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["content"] = I("post.content", "", "str_filter");	
			$row["dataclass_id"] = I("post.dataclass_id", 0, "intval");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["type"] = I("post.type", 0, "intval");		
			$row["picture"] = "";
			
			if(!$row["name"])
				$this->resFail(1, "名称不能为空");
			elseif(!$row["content"])
				$this->resFail(1, "内容不能为空");
			
			if($id != 0) {
				
				D("Data")->save($row);
				$this->resSuccess("更新成功");
			}
			else {
				unset($row["id"]);
				$row["add_time"] = time();
				D("Data")->add($row);
				$this->resSuccess("添加成功");
			}
		}
		
		$this->assign("row", $row);		
		$this->display();
	}
	
	public function data_del() {
		$id = I("request.id", 0, "intval");
		D("Data")->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
	public function feedback_list() {
		$get_data = I("request.get_data", NULL);
		if($get_data) {
			$page = I("request.page", 1, "intval");
			$page_size = I("request.page_size", C("WEB_PAGE_SIZE"), "intval");
			$res_data = D("Feedback")->getList($page, $page_size);
			
			$this->resSuccess("请求成功", $res_data);
		}
		$this->display();
	}
	
	public function feedback_del() {
		$id = I("request.id", 0, "intval");
		D("Feedback")->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
	public function friendlink() {
		//这个方法没有命名friendlink_list是因为左边的菜单列表需要跟friendlink_add的url匹配
		
		$page = I("request.page", 1, "intval");
		$page_size = I("request.page_size", C("WEB_PAGE_SIZE"), "intval");
		$res_data = D("Friendlink")->getList($page, $page_size);
		$this->assign("data", $res_data);
			
		$this->display();
	}
	
	public function friendlink_add() {
		$id = I("request.id", 0, "intval");
				
		$row = NULL;
		if($id) {
			$row = D("Friendlink")->where("id = " . $id)->find();			
		}
		else {
			$row = array(
				"id" => 0,
				"name" => "",
				"url" => "",
				"picture" => "",
				"sort" => 0,
				"is_picture" => 0
			);			
		}
		
		if(IS_POST) {
			
			$row["name"] = I("post.name", "", "str_filter");
			$row["url"] = I("post.url", "", "str_filter");	
			$row["picture"] = I("post.picture", "", "str_filter");
			$row["sort"] = I("post.sort", 0, "intval");
			$row["is_picture"] = I("post.is_picture", 0, "intval");
			
			if(!$row["name"])
				$this->resFail(1, "名称不能为空");
			elseif(!$row["url"])
				$this->resFail(1, "URL不能为空");
			
			if($id != 0) {
				
				D("Friendlink")->save($row);
				$this->resSuccess("更新成功");
			}
			else {
				unset($row["id"]);
				D("Friendlink")->add($row);
				$this->resSuccess("添加成功");
			}
		}
		
		$this->assign("row", $row);
		$this->display();
	}
	
	public function friendlink_del() {
		$id = I("request.id", 0, "intval");
		D("Friendlink")->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
}
