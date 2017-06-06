<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    'url_route_on' => true,
    /*'log' => [
        'type' => 'trace', // 支持 socket trace file
    ],*/
	'cache' => [
        'type' => 'File',
        'prefix' => 't_'
    ],
	'default_module' => 'site',
	'default_action' => 'index',
	'default_lang' => 'zh-cn',
	'show_error_msg' => true,
	'session_auto_start' => true,
	'default_ajax_return' => 'html',
	
	//以下是本项目的私有配置
	'web_root' => 'http://192.168.199.102/thinkphp-ozgweb/public/',
	'web_res_root' => 'http://192.168.199.102/thinkphp-ozgweb/public/static/',
		
];
