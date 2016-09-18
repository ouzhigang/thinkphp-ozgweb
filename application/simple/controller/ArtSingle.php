<?php
namespace app\simple\controller;

use \think\Response;
use \think\Request;

class ArtSingle extends Base {
	
	public function get() {
		$id = input("request.id", 0, "intval");
		
		$data = \app\common\model\ArtSingle::findById($id);
		
		if(Request::instance()->isPOST()) {
			$content = input("post.content", "", "str_filter");			
			$data["content"] = $content;			
			\app\common\model\ArtSingle::saveData($data, $id);		

			return res_result(NULL, 0, "更新成功");
		}
		
		$data["content"] = html_entity_decode($data["content"]);
		$this->assign("data", $data);
		return $this->fetch("get");
	}
	
}
