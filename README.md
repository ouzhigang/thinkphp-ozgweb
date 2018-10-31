###部署步骤

	1.使用vue的后台，cd到thinkphp-ozgweb/vue/mgr，然后npm i && npm run build，修改前端的路径在thinkphp-ozgweb/vue/mgr/src/components/common/common.js

	
	2.使用react的后台，cd到thinkphp-ozgweb/react/mgr，然后npm i && build.sh，修改前端的路径在react/mgr/src/common.js
	

	3.thinkphp5需要自行安装
	
	
	4.验证码部分的配置，https://github.com/top-think/think-captcha
	
	
	5.nginx运行需要在server节加入如下配置	
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

后台入口：thinkphp-ozgweb/mgr


后台用户密码都是admin。
