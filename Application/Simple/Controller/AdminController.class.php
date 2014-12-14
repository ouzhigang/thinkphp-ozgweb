<?php
namespace Simple\Controller;
use Simple\Controller\BaseController;
use Simple\Model\AdminModel;
use Simple\Model\ArtSingleModel;
use Simple\Model\DataClassModel;
use Simple\Model\DataModel;

class AdminController extends BaseController {
	
    function index() {
		if(session("?sess_admin"))
			redirect("admin");
		
        $this->display();
    }
	
	function admin() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			redirect("index");
		
		$this->assign("sys", PHP_OS);
		$this->assign("php_ver", PHP_VERSION);		
		$this->assign("thinkphp_ver", THINK_VERSION);
		
		$this->display();
    }
	
	function get_code() {
		$verify = new \Think\Verify();
		$verify->fontSize = 14;
		$verify->length   = 4;
		$verify->useNoise = false;
		$verify->codeSet = '0123456789';
		$verify->imageW = 120;
		$verify->imageH = 30;
		//$verify->expire = 600;
		$verify->entry(1);		
	}
	
	function ajax_login() {
		
		$imgcode = $_REQUEST["code"];
		if(!$imgcode)
			$this->resFail(1, "验证码不能为空");
		
		$verify = new \Think\Verify();
		if($verify->check($imgcode, 1)) {
			$name = htmlspecialchars($_REQUEST["name"]);
			$pwd = htmlspecialchars($_REQUEST["pwd"]);
			
			$m = M("Admin");
			$admin = $m->where("name = '" . $name . "' and pwd = '" . $pwd . "'")->find();
			if($admin) {
				
				session("sess_admin", $admin);
				unset($admin["pwd"]);
				$this->resSuccess("登录成功");
			}
			else
				$this->resFail(1, "用户或密码不正确");
		}
		else
			$this->resFail(1, "验证码不正确");
	}
	
	function ajax_logout() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		session("sess_admin", NULL);
		$this->resSuccess("退出登录");
	}
	
	function ajax_menu_list() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$this->resSuccess("请求成功", C("admin_menu_list"));
	}
	
	function ajax_admin_list() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		//分页索引和每页显示数
		$page = 1;
		if(isset($_REQUEST["page"]))
			$page = intval($_REQUEST["page"]);
		$page_size = C("page_size");
		if(isset($_REQUEST["page_size"]))
			$page_size = intval($_REQUEST["page_size"]);
		
		$this->resSuccess("请求成功", AdminModel::getList($page, $page_size));
	}
	
	function ajax_admin_add() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$name = htmlspecialchars($_REQUEST["name"]);
		$pwd = htmlspecialchars($_REQUEST["pwd"]);
		$pwd2 = $_REQUEST["pwd2"];
		if(!$name)
			$this->resFail(1, "用户名不能为空");
		if(!$pwd)
			$this->resFail(1, "密码不能为空");
		if($pwd != $pwd2)
			$this->resFail(1, "确认密码不正确");
		
		$m = M("Admin");
		$total = $m->field("count(id) as total")->where("name = '" . $name . "'")->find();
		$total = $total["total"];
		if($total > 0)
			$this->resFail(1, "该管理员已存在");
			
		$admin = array(
			"name" => $name,
			"pwd" => $pwd,
			"add_time" => time()
		);
		$m->add($admin);
		$this->resSuccess("添加成功");
		
	}
	
	function ajax_admin_del() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = intval($_REQUEST["id"]);
		$m = M("Admin");
		$result = $m->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");
	}
	
	function ajax_admin_updatepwd() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$curr_admin = session("sess_admin");
		$old_pwd = htmlspecialchars($_REQUEST["old_pwd"]);
		$pwd = htmlspecialchars($_REQUEST["pwd"]);
		$pwd2 = $_REQUEST["pwd2"];
		if(!$old_pwd)
			$this->resFail(1, "旧密码不能为空");
		if(!$pwd)
			$this->resFail(1, "新密码不能为空");
		if($pwd != $pwd2)
			$this->resFail(1, "确认密码不正确");
		
		$m = M("Admin");
		$admin = $m->where("name = '" . $curr_admin["name"] . "' and pwd = '" . $old_pwd . "'")->find();
		if($admin) {
			$admin["pwd"] = $pwd;
			$m->save($admin);
			$this->resSuccess("修改密码成功");
		}
		else
			$this->resFail(1, "旧密码不正确");
	}
	
	function ajax_art_single_get() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = intval($_REQUEST["id"]);
		$m = M("ArtSingle");
		$art = $m->where("id = " . $id)->find();
		$this->resSuccess("请求成功", $art);
	}
	
	function ajax_art_single_update() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = intval($_REQUEST["id"]);
		$content = $_REQUEST["content"];
		$m = M("ArtSingle");
		$art = $m->where("id = " . $id)->find();
		$art["content"] = $content;
		$m->save($art);
		
		$this->resSuccess("更新成功");
	}
	
	function ajax_dataclass_list() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$type = intval($_REQUEST["type"]);
		$m = M("DataClass");
		$dataclass_list = $m->where("type = " . $type . " and parent_id = 0")->order("sort desc, id desc")->select();
		foreach($dataclass_list as &$v) {
			$child_count = $m->field("count(id) as total")->where("parent_id = " . $v["id"])->find();
			$child_count = $child_count["total"];
			if($child_count > 0)
				$v["children"] = DataClassModel::listById($v["id"]);
			
		}
		$this->resSuccess("请求成功", $dataclass_list);
	}
	
	function ajax_dataclass_get() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = intval($_REQUEST["id"]);
		$m = M("DataClass");
		$dataclass = $m->where("id = " . $id)->find();
		if($dataclass["parent_id"] != 0)
			$dataclass["parent"] = DataClassModel::getById($dataclass["parent_id"]);
		$this->resSuccess("请求成功", $dataclass);
	}
	
	function ajax_dataclass_add() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		$id = 0;
		if(isset($_REQUEST["id"]))
			$id = intval($_REQUEST["id"]);
		$name = htmlspecialchars($_REQUEST["name"]);
		$parent_id = htmlspecialchars($_REQUEST["parent_id"]);
		
		$m = M("DataClass");
		$dataclass = NULL;
		if($id != 0) {
			if($id == $parent_id)
				$this->resFail(1, "父级分类不能为当前选中分类");
			$dataclass = $m->where("id = " . $id)->find();			
		}
		else
			$dataclass = array();
		
		$dataclass["name"] = $name;
		$dataclass["parent_id"] = $parent_id;
		$dataclass["sort"] = intval($_REQUEST["sort"]);
		$dataclass["type"] = intval($_REQUEST["type"]);
		$m->save($dataclass);
		if($id != 0)
			$this->resSuccess("更新成功");
		else
			$this->resSuccess("添加成功");
		
	}
	
	function ajax_dataclass_del() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = intval($_REQUEST["id"]);
		$m = M("DataClass");
		$dataclass = $m->where("id = " . $id)->find();
		$child_count = $m->field("count(id) as total")->where("parent_id = " . $dataclass["id"])->find();
		$child_count = $child_count["total"];
		
		if($child_count > 0)
			DataClassModel::deleteById($dataclass["id"]);
		
		//删除该分类下面的对应数据
		$m2 = M("Data");
		$m2->where("dataclass_id = " . $dataclass["id"])->delete();
		$m->where("id = " . $dataclass["id"])->delete();
		$this->resSuccess("删除成功");
	}
	
	function ajax_data_list() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
			
		//分页索引和每页显示数
		$page = 1;
		if(isset($_REQUEST["page"]))
			$page = intval($_REQUEST["page"]);
		$page_size = C("page_size");
		if(isset($_REQUEST["page_size"]))
			$page_size = intval($_REQUEST["page_size"]);
		$type = intval($_REQUEST["type"]);
		
		$res_data = DataModel::getList($page, $page_size, $type);
		$this->resSuccess("请求成功", $res_data);
	}
	
	function ajax_data_get() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = intval($_REQUEST["id"]);
		$m = M("Data");
		$data = $m->where("id = " . $id)->find();
		$this->resSuccess("请求成功", $data);		
	}
	
	function ajax_data_add() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
		
		$id = 0;
		if(isset($_REQUEST["id"]))
			$id = intval($_REQUEST["id"]);
		$name = htmlspecialchars($_REQUEST["name"]);
		$content = $_REQUEST["content"];
		
		if(!$name)
			$this->resFail(1, "名称不能为空");
		elseif(!$content)
			$this->resFail(1, "内容不能为空");
		
		$m = M("Data");
		$data = NULL;
		if($id != 0)
			$data = $m->where("id = " . $id)->find();
		else
			$data = array();
		$data["name"] = name;
		$data["content"] = content;
		$data["add_time"] = intval(time());
		$data["dataclass_id"] = intval($_REQUEST["dataclass_id"]);
		$data["sort"] = intval($_REQUEST["sort"]);
		$data["type"] = intval($_REQUEST["type"]);
		$data["hits"] = 0;
		$data["picture"] = "";
		$m.save($data);
		if($id != 0)
			$this->resSuccess("更新成功");
		else
			$this->resSuccess("添加成功");
	}
	
	function ajax_data_del() {
		//需要登录才可以访问
		if(!session("?sess_admin"))
			$this->resFail(1, "需要登录才可以访问");
			
		$id = intval($_REQUEST["id"]);
		$m = M("Data");
		$m->where("id = " . $id)->delete();
		$this->resSuccess("删除成功");	
	}
	
}
