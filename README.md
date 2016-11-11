###部署步骤

	1.需要根据实际情况设置thinkphp-ozgweb/application/config.php的web_res_root部分（资源文件的目录）和web_root部分（网站的根目录）

	
	2.需要根据实际情况设置thinkphp-ozgweb/public/static/simple/js/cfg.js的web_res_root部分（资源文件的目录）和web_root部分（网站的根目录）
	

	3.sb-admin2需要自行下载，https://github.com/BlackrockDigital/startbootstrap-sb-admin-2 ，对应目录thinkphp-ozgweb/public/static/startbootstrap-sb-admin-2


	4.ckeditor需要自行下载，对应目录thinkphp-ozgweb/public/static/ckeditor


	5.thinkphp5需要自行安装
	
	
	6.验证码部分的配置，https://github.com/top-think/think-captcha


	7.在thinkphp-ozgweb目录下执行一次npm install，然后执行一次webpack

	
	8.nginx运行需要在server节加入如下配置	
	location /thinkphp-ozgweb/public/static/ {
		access_log off;
		expires 10d;
	}
	location /thinkphp-ozgweb/public/ {
		if (!-e $request_filename) {
			rewrite ^/thinkphp-ozgweb/public/(.*)$ /thinkphp-ozgweb/public/index.php?s=$1 last;
			break;
		}
	}
	

==========

后台入口：thinkphp-ozgweb/public/simple/index/login


后台用户密码都是admin。暂无前台。
