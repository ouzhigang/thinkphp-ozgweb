<?php
return array(
	"web_global_title" => "ozgweb",	
	"page_size" => 16,
	
	//后台菜单，只支持3级
	"admin_menu_list" => [
		array(
			"id" => 1,
			"name" => "后台管理",
			"selected" => true,
			"child_menu" => array(
				array(
					"id" => 2,
					"name" => "数据管理",
					"child_menu" => array(
						array(
							"id" => 5,
							"name" => "分类列表",
							"url" => "dataclass_list.html",
							"param" => "type:1", #demo type:1,id:2
						),
						array(
							"id" => 6,
							"name" => "数据列表",
							"url" => "data_list.html",
							"param" => "type:1",
						)
						
					)
				),
				array(
					"id" => 3,
					"name" => "区域管理",
					"child_menu" => array(
						array(
							"id" => 7,
							"name" => "区域管理1",
							"url" => "art_single.html",
							"param" => "id:1",
						)					
					)
				),
				array(
					"id" => 4,
					"name" => "管理员管理",
					"child_menu" => array(
						array(
							"id" => 8,
							"name" => "修改密码",
							"url" => "admin_pwd.html",
						),
						array(
							"id" => 9,
							"name" => "管理员列表",
							"url" => "admin_list.html",
						)					
					)
				)
			)
		)
	],
	
	'DB_TYPE' => 'pdo',
    'DB_DSN' => 'sqlite:simple.sqlite3',
    'DB_PREFIX' => 'simple_', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 数据库编码默认采用utf8
    'DB_FIELDS_CACHE' => false, // 启用字段缓存
);
