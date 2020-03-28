###部署步骤

    1.需要根据实际情况设置thinkphp-ozgweb/react/mgr/src/common.js或thinkphp-ozgweb/vue/mgr/src/components/common/common.js，默认已设好

	
	2.执行composer update


    3.把.example.env改成.env


    4.执行php think swoole


    5.cd到thinkphp-ozgweb/react/mgr，执行npm i && ./build.sh。
    或cd到thinkphp-ozgweb/vue/mgr，执行npm i && npm run build
	
	
	6.设置nginx目录thinkphp-ozgweb/public，然后在server节加入如下配置
    
	#静态文件用到
    location ~ .*\.(css|js|jpg|jpeg|gif|png|mp3|apk|zip|txt|eot|html)$ {
        expires 24h;
        root /usr/share/nginx/html;
    }

    location /mgr/ {
        expires 24h;
        root /usr/share/nginx/html;
    }

    location /server/ {
        #运行swoole的ip
        proxy_pass http://172.17.0.2:8101/;
        #下边是为获取真实IP所做的设置
        proxy_set_header    X-Real-IP        $remote_addr;
        proxy_set_header    X-Forwarded-For  $proxy_add_x_forwarded_for;
        proxy_set_header    HTTP_X_FORWARDED_FOR $remote_addr;
        proxy_set_header    X-Forwarded-Proto $scheme;
        proxy_redirect      default;
    }
	
	
	
==========

入口：/mgr/


后台用户密码都是admin。
