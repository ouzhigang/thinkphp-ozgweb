<?php
namespace app\common\model;

class User extends Base {
    
	public static function getList($page, $page_size) {
		
		$total = parent::where("is_admin = 1")->count();
		
		$page_count = page_count($total, $page_size);
		
		$offset = ($page - 1) * $page_size;
		$limit = $page_size;
		
		$list = parent::all(function($query) use($offset, $limit) {
			$query->where("is_admin = 1")->limit($offset, $limit)->order([ "id" => "desc" ]);
		});
		
		foreach($list as &$v){
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
		}
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
	public static function adminLogin($name, $pwd, $remember = 0, $vcode = "") {

		$user = parent::where("name = '" . $name . "' and is_admin = 1")->find();
		if($user) {
			
			if($user["err_login"] >= 3) {
				
				if($vcode == "") {
					return [
						"code" => 2
					];
				}
				else {
					$verify = new \org\Verify();
					if(!$verify->check($vcode, 1)) {
						return [
							"code" => 1, 
							"desc" => "验证码错误"
						];
					}
				}
			}
			
			$user = $user->toArray();
			if($user["pwd"] == $pwd) {
				session("user", $user);
				
				$user["err_login"] = 0;
				
				$id = $user["id"];
				unset($user["id"]);
				parent::where("id = " . $id)->update($user);
				
				if($remember == 1) {
					$curr_user_name = \utility\Encrypt::encode($user["name"]);
					
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
				parent::where("id = " . $id)->update($user);
				
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
	
	public static function delById($id = 0) {
		
		$user = session("user");
		if($user["id"] == $id) {
			return [
				"code" => 1,
				"desc" => "不能删除自己"
			];
		}
		
		$result = parent::where("id = " . $id)->delete();
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
	public static function logout() {
	
		session("user", NULL);
		
		if(isset($_COOKIE["curr_user_name"]))
			cookie("curr_user_name", null);
			
		return [
			"code" => 0,
			"desc" => "退出成功"
		];
	}
	
	public static function findByName($name, $other = []) {
		$where = [
			"name" => $name
		];		
		$where = array_merge($where, $other);
		$data = parent::where($where)->find();
		return $data->toArray();
	}
	
	public static function saveData($data, $id = 0) {
		
		if($id) {
			parent::where("id = " . $id)->update($data);
		}
		else {
			$total = parent::where("name = '" . $data["name"] . "'")->count();
			if($total > 0) {
				return [
					"code" => 1,
					"desc" => "该用户已存在"
				];
			}
			
			$data["add_time"] = time();
			$data["is_admin"] = 1;
			
			parent::create($data);
			return [
				"code" => 0,
				"desc" => "添加成功"
			];
		}
		return true;
	}
	
	public static function updatePwd($old_pwd, $pwd, $pwd2) {
		$curr_user = session("user");
		
		$user = parent::where("name = '" . $curr_user["name"] . "' and pwd = '" . $old_pwd . "'")->find();		
		if($user) {
			$user = $user->toArray();
			$user["pwd"] = $pwd;
			$id = $user["id"];
			unset($user["id"]);
			parent::where("id = " . $id)->update($user);
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
