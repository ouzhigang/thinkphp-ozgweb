<?php
namespace app\common\model;

class Data extends Base {
	
	public static function getList($page, $page_size, $type, $wq = "") {
		if($wq != "") {
			$wq = " and " . $wq;
		}

		$total = self::whereRaw("type = " . $type . $wq)->count();
		$page_count = page_count($total, $page_size);
		
		$list = self::where(function($query) use($type, $wq, $page, $page_size) {
			$query->whereRaw("type = " . $type . $wq)
				->order("sort", "desc")
				->order("id", "desc")
				->page($page, $page_size);
		})->select();
		
		foreach($list as &$v) {
			$v["add_time_s"] = date("Y-m-d H:i:s", $v["add_time"]);
			$v["dc_name"] = $v->dataCat ? $v->dataCat["name"] : "[暂无]";
			$v["picture"] = json_decode($v["picture"], true);
			$v["picture_0"] = count($v["picture"]) > 0 ? $v["picture"][0] : "";
			$v["content"] = html_entity_decode($v["content"]);
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
		$data = self::where("id", $id)->find();
		if($data) {
			$data = $data->toArray();
			$data["picture"] = json_decode($data["picture"], true);
			$data["content"] = html_entity_decode($data["content"]);
			return $data;
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
			self::where("id", $id)->update($data);
			return res_result(NULL, 0, "修改成功");
		}
		else {
			unset($data["id"]);
			$data["add_time"] = time();
			self::create($data);
			
			return res_result(NULL, 0, "添加成功");
		}
	}
	
	public static function delById($ids = []) {
		$result = self::whereIn("id", $ids)->delete();
		if($result) {
			return res_result(NULL, 0, "删除成功");
		}
		
		return res_result(NULL, 1, "删除失败");
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
	
	public function dataCat() {
    
    	//mysql
    	//belongsTo('关联模型名','本表的外键名','关联表主键名',['模型别名定义'],'join类型');
        return $this->belongsTo('DataCat', 'data_cat_id', 'id');
    }
    
}
