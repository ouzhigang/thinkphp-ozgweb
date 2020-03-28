<?php
namespace app\mgr\controller;

class ArtSingle extends \app\BaseController {
	
	protected $middleware = [ '\app\middleware\UserCheck' ];

	public function get() {
		$id = input("param.id/d", 0);
		
		$data = \app\common\model\ArtSingle::findById($id);
		
		$data["content"] = html_entity_decode($data["content"]);
		
		return json(res_result($data, 0, "请求成功"));
	}
	
	public function update() {
		$id = input("param.id/d", 0);
		$content = input("param.content/s", "");			
		$data = [
			"content" => $content
		];			
		\app\common\model\ArtSingle::saveData($data, $id);
			
		$res = res_result(NULL, 0, "更新成功");
		return json($res);
	}
	
}
