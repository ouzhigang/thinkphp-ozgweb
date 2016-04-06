<?php
namespace app\common\model;

class User extends Base {
    
	public function getList($page, $page_size) {
		
		$total = $this->field("count(id) as total")->where("is_admin = 1")->find();
		$total = $total["total"];
		
		$page_count = page_count($total, $page_size);
		
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;

		$list = $this->where("is_admin = 1")->order("id desc")->limit($offset . ", " . $limit)->select();
		foreach($list as &$v)
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
		
		$r = [
			"page_size" => $page_size,
			"page_count" => $page_count,
			"total" => intval($total),
			"page" => $page,
			"list" => $list,
		];		
		return $r;
	}
	
	//管理员登录
	public function adminLogin($name, $pwd, $remember = 0, $vcode = "") {
		
		$user = $this->where("name = '" . $name . "' and is_admin = 1")->find();
		if($user) {
			
			if($user["err_login"] >= 3) {
				
				if($vcode == "") {
					return [
						"code" => 2
					];
				}
				else {
					$verify = new \Think\Verify();
					if(!$verify->check($vcode, 1)) {
						return [
							"code" => 1, 
							"desc" => "验证码错误"
						];
					}
				}
			}
			
			if($user["pwd"] == $pwd) {
				
				session("user", $user);
				
				$user["err_login"] = 0;
				
				$this->data($user)->save();
				
				if($remember == 1) {
					$curr_user_name = \Common\Encrypt::encode($user["name"]);
					
					cookie('curr_user_name', $curr_user_name, ['expire' => 86400 * 7]); //保存7天
					//echo $curr_user_name;exit();
				}
				
				$arr = [
					"code" => 0, 
					"desc" => "登录成功"
				];
			}
			else {				
				$user["err_login"] += 1;
				$id = $user["id"];
				unset($user["id"]);
				$this->where("id = " . $id)->save($user);
				
				$arr = [
					"code" => 1, 
					"desc" => "密码错误"
				];
			}		
			
			return $arr;
		}
		else {
			$arr = [
				"code" => 1, 
				"desc" => "没有此用户"
			];
			return $arr;
		}
	}
	
	public function delById($id = 0) {
		
		$user = session("user");
		if($user["id"] == $id) {
			return [
				"code" => 1,
				"desc" => "不能删除自己"
			];
		}
		
		$result = $this->where("id = " . $id)->delete();
		if($result) {
			return [
				"code" => 0,
				"desc" => "删除成功"
			];
		}
		
		return [
			"code" => 1,
			"desc" => "删除失败"
		];
	}
	
	//退出登录
	public function logout() {
	
		session("user", NULL);
		
		if(isset($_COOKIE["curr_user_name"]))
			cookie("curr_user_name", null);
			
		return [
			"code" => 0,
			"desc" => "退出成功"
		];
	}
	
	public function findByName($name, $other = []) {
		$where = [
				"name" => $name
		];		
		$where = array_merge($where, $other);
		$data = $this->where($where)->find();
		return $data;
	}
	
	public function saveData($data, $id = 0) {
		
		if($id) {
			$this->where([
				"id" => $id
			])->save($data);
		}
		else {
			$total = $this->field("count(id) as total")->where("name = '" . $data["name"] . "'")->find();
			$total = $total["total"];
			if($total > 0) {
				return [
					"code" => 1,
					"desc" => "该用户已存在"
				];
			}
			
			$data["add_time"] = time();
			$data["is_admin"] = 1;
			
			$this->add($data);
			return [
				"code" => 0,
				"desc" => "添加成功"
			];
		}
		return true;
	}
	
	public function updatePwd($old_pwd, $pwd, $pwd2) {
		$curr_user = session("user");
		
		$user = $this->where("name = '" . $curr_user["name"] . "' and pwd = '" . $old_pwd . "'")->find();
		if($user) {
			$user["pwd"] = $pwd;
			$this->save($user, $user["id"]);
			return [
				"code" => 0,
				"desc" => "修改密码成功"
			];
		}
		else {
			return [
				"code" => 1,
				"desc" => "旧密码不正确"
			];
		}
		
	}
	
}
