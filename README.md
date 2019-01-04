###部署步骤

	1.使用vue的后台，cd到thinkphp-ozgweb/vue/mgr，然后npm i && npm run build，修改前端的路径在thinkphp-ozgweb/vue/mgr/src/components/common/common.js

	
	2.使用react的后台，cd到thinkphp-ozgweb/react/mgr，然后npm i && ./build.sh，修改前端的路径在react/mgr/src/common.js
	

	3.执行composer update topthink/framework  安装tp5
	
	
	4.执行composer require topthink/think-captcha   安装验证码库
	
	
	5.nginx运行需要在server节加入如下配置	
	location /server/mgr/ {
		if (!-e $request_filename) {
			rewrite ^/server/(.*)$ /index.php?s=$1 last;
			break;
		}
	}
	location / {
		if (!-e $request_filename) {
			rewrite ^/(.*)$ /$1 last;
			break;
		}
	}
	

==========

后台入口：/mgr


后台用户密码都是admin。
