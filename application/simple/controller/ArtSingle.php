<?php
namespace app\simple\controller;

class ArtSingle extends Base {
	
	public function get() {
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
	
}
