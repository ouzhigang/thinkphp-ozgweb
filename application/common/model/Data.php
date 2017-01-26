<?php
namespace app\common\model;

class Data extends Base {
	
	public static function getList($page, $page_size, $type, $wq = "") {
		if($wq != "")
			$wq = " and " . $wq;
		
		$total = \think\Db::query('select count(d.id) as total from ' . config("database.prefix") . 'data as d left join ' . config("database.prefix") . 'data_class as dc on d.data_class_id = dc.id where 1 = 1' . $wq);
		$total = intval($total[0]["total"]);
		$page_count = page_count($total, $page_size);
		
		$list = self::select(function($query) use($type, $wq, $page, $page_size) {
			$query->where("d.type = " . $type . " " . $wq)
				->alias("d")
				->field("d.*, dc.name as dc_name")
				->join("data_class dc", "d.data_class_id = dc.id", "left")
				->order("d.sort desc, d.id desc")
				->page($page, $page_size);
		});
		
		foreach($list as &$v) {
			$v["add_time"] = date("Y-m-d H:i:s", $v["add_time"]);
			$v["dc_name"] = $v["dc_name"] ? $v["dc_name"] : "[暂无]";
			$v["picture"] = json_decode($v["picture"], true);
			$v["picture"] = count($v["picture"]) > 0 ? $v["picture"][0] : "";
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
	
	public static function findById($id) {
		$data = self::where("id = " . $id)->find();
		if($data) {
			$data["picture"] = json_decode($data["picture"], true);
			return $data->toArray();
		}
		return NULL;
	}
	
	public static function saveData($data, $id = 0) {
		
		if(!$data["name"]) {
			return res_result(NULL, 1, "名称不能为空");
			
		}
		elseif(!$data["content"]) {
			return res_result(NULL, 1, "内容不能为空");
		}
		
		if($id) {
			self::where("id = " . $id)->update($data);
			return res_result(NULL, 0, "修改成功");
		}
		else {
			unset($data["id"]);
			$data["add_time"] = time();
			self::create($data);
			
			return res_result(NULL, 0, "添加成功");
		}
	}
	
	public static function delById($id = 0) {
		self::where("id = " . $id)->delete();
		return true;
	}
	
	public static function upHits($id = 0) {
		
		$history = [];
		if(cookie('history')) {
			$history = cookie('history');
			$history = explode(",", $history);
		}
		
		if(!in_array($id, $history)) {
		
			$data = self::findById($id);
			if($data) {
				$data["hits"]++;
				self::saveData($data, $id);
			}
			
			$history[] = $id;
			
			cookie('history', implode(",", $history), 86400 * 30);
		}
		
		return true;
	}
	
}
