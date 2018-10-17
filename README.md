###部署步骤

	1.cd到thinkphp-ozgweb/vue/mgr，然后npm i，修改前端的路径在thinkphp-ozgweb/vue/mgr/src/components/common/common.js


	2.thinkphp5需要自行安装
	
	
	3.验证码部分的配置，https://github.com/top-think/think-captcha
	
	
	4.nginx运行需要在server节加入如下配置	
	location /thinkphp-ozgweb/server/mgr/ {
		if (!-e $request_filename) {
			rewrite ^/thinkphp-ozgweb/server/(.*)$ /thinkphp-ozgweb/public/index.php?s=$1 last;
			break;
		}
	}
	location /thinkphp-ozgweb/ {
		if (!-e $request_filename) {
			rewrite ^/thinkphp-ozgweb/(.*)$ /thinkphp-ozgweb/public/$1 last;
			break;
		}
	}
	

==========

前台入口：thinkphp-ozgweb/mgr


后台用户密码都是admin。
