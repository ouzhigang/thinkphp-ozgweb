###部署步骤

	1.需要根据实际情况设置thinkphp-ozgweb/application/config.php的web_res_root部分（资源文件的目录）和web_root部分（网站的根目录）

	
	2.需要根据实际情况设置thinkphp-ozgweb/public/static/mgr/js/cfg.js、thinkphp-ozgweb/public/static/site/js/cfg.js的web_res_root部分（资源文件的目录）和web_root部分（网站的根目录）
	

	3.sb-admin2需要自行下载，https://github.com/BlackrockDigital/startbootstrap-sb-admin-2 ，对应目录thinkphp-ozgweb/public/static/startbootstrap-sb-admin-2


	4.thinkphp5需要自行安装
	
	
	5.验证码部分的配置，https://github.com/top-think/think-captcha
	
	
	6.nginx运行需要在server节加入如下配置	
	location /thinkphp-ozgweb/static/ {
            if (!-e $request_filename) {
                    rewrite ^/thinkphp-ozgweb/static/(.*)$ /thinkphp-ozgweb/public/static/$1 last;
                    break;
            }
    }
    location /thinkphp-ozgweb/ {
            if (!-e $request_filename) {
                    rewrite ^/thinkphp-ozgweb/(.*)$ /thinkphp-ozgweb/public/index.php?s=$1 last;
                    break;
            }
    }
	

==========

前台入口：thinkphp-ozgweb/

后台入口：thinkphp-ozgweb/mgr


后台用户密码都是admin。
