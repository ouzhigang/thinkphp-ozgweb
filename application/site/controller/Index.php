<?php
namespace app\site\controller;

use \think\Response;
use \app\common\model\ArtSingle;
use \app\common\model\DataClass;
use \app\common\model\Data;

class Index extends Base {
	
	protected $beforeActionList = [
		"commonData" => [
			'only' => 'index,art,productview,newsview,newslist,productlist'
		]
    ];
	
	protected function commonData() {
		
		//内页左边 产品类别
		$product_class_list = DataClass::getList(1);
		$this->assign("product_class_list", $product_class_list);
		
		//内页左边 推荐产品
		$product_list_recommend = Data::getList(1, 5, 1, "d.recommend = 1");
		$this->assign("product_list_recommend", $product_list_recommend["list"]);
		
		//内页左边 行业新闻
		$news_list_recommend = Data::getList(1, 5, 2, "d.recommend = 1");
		$this->assign("news_list_recommend", $news_list_recommend["list"]);
		
		//内页左边 联系我们(内页)
		$art_7 = ArtSingle::findById(7);
		$this->assign("art_7", html_entity_decode($art_7["content"]));
		
		//页脚
		$art_8 = ArtSingle::findById(8);
		$this->assign("art_8", html_entity_decode($art_8["content"]));
	}
	
    public function index() {
		
		//新闻资讯
		$news_list_top = Data::getList(1, 1, 2, "d.is_index_top = 1");
		if(count($news_list_top) <= 0)
			$news_list_top = Data::getList(1, 1, 2);
		if(count($news_list_top) <= 0) {
			$news_list_top = [
				"list" => [
					[
						"id" => 0, 
						"name" => "", 
						"content" => ""
					]
				]
			];
		}
		$news_list_top["list"][0]["content"] = strip_tags(html_entity_decode($news_list_top["list"][0]["content"]));
		$news_list_top["list"][0]["content"] = get_string($news_list_top["list"][0]["content"], 50);
		$this->assign("news_list_top", $news_list_top["list"][0]);		
		$news_list = Data::getList(1, 4, 2, "d.is_index_show = 1 and d.id <> " . $news_list_top["list"][0]["id"]);
		foreach($news_list["list"] as &$v) {
			$v["add_time"] = strtotime($v["add_time"]);
			$v["add_time"] = date("Y-m-d", $v["add_time"]);
		}
		$this->assign("news_list", $news_list["list"]);
		//新闻资讯 end
		
		//滚动产品
		$product_list = Data::getList(1, 10, 1, "d.is_index_show = 1");
		$this->assign("product_list", $product_list["list"]);
		//滚动产品 end
		
		//公司简介
		$art_2 = ArtSingle::findById(2);
		$this->assign("art_2", html_entity_decode($art_2["content"]));
		
		//联系我们(首页)
		$art_6 = ArtSingle::findById(6);
		$this->assign("art_6", html_entity_decode($art_6["content"]));
		
		$this->assign("page_header_nav_selected", 1);
		$this->assign("title", "首页");
		return $this->fetch("index");
    }
	
	public function art() {
		$id = input("param.id", 0, "intval");
		
		$art = ArtSingle::findById($id);
		$this->assign("content", html_entity_decode($art["content"]));
		
		if($id == 1)
			$this->assign("page_header_nav_selected", 2);
		elseif($id == 3)
			$this->assign("page_header_nav_selected", 5);
		elseif($id == 4)
			$this->assign("page_header_nav_selected", 6);
		elseif($id == 5)
			$this->assign("page_header_nav_selected", 7);
		
		$this->assign("title", $art["name"]);
		return $this->fetch("art");
    }
	
	public function productView() {
		
		$id = input("param.id", 0, "intval");
		$data = Data::findById($id);
		$data["add_time"] = date("Y-m-d H:i:s", $data["add_time"]);
		$data["content"] = html_entity_decode($data["content"]);
		$this->assign("data", $data);
		
		Data::upHits($id);
		
		$this->assign("page_header_nav_selected", 4);
		return $this->fetch("product_view");
    }
	
	public function productList() {
		
		$this->assign("page_header_nav_selected", 4);
		$this->assign("title", "产品中心");
		return $this->fetch("product_list");
    }
	
	public function newsView() {
		$id = input("param.id", 0, "intval");
		$data = Data::findById($id);
		$data["add_time"] = date("Y-m-d H:i:s", $data["add_time"]);
		$data["content"] = html_entity_decode($data["content"]);
		$this->assign("data", $data);
				
		Data::upHits($id);
		
		$this->assign("page_header_nav_selected", 3);
		return $this->fetch("news_view");
    }
	
	public function newsList() {
		
		$this->assign("page_header_nav_selected", 3);
		$this->assign("title", "新闻中心");
		return $this->fetch("news_list");
    }
	
	public function getNewsList() {
		$page = input("param.page", 1, "intval");
		$page_size = 40;
		
		$data = Data::getList($page, $page_size, 2);
		return json(res_result($data, 0, "请求成功"));
	}
		
	public function getProductList() {
		$page = input("param.page", 1, "intval");
		$search = input("param.search", NULL, "str_filter");
		$data_class_id = input("param.data_class_id", 0, "intval");
		
		$page_size = 40;
		
		$wq = " 1 = 1 ";
		if($search)
			$wq .= " and d.name like '%" . $search . "%' ";
		if($data_class_id)
			$wq .= " and d.data_class_id = " . $data_class_id;
		
		$data = Data::getList($page, $page_size, 1, $wq);
		return json(res_result($data, 0, "请求成功"));	
	}
	
}
