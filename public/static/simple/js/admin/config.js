
//后台js配置

require.config({
    paths: {
        "jquery": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/jquery/jquery.min",
		"jquery_ui": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/jquery/jquery-ui.min",
		"jquery_validate": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/jquery/jquery.validate.min",
		"jquery_LinkageList": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/jquery/jquery.LinkageList",
		"bootstrap": "http://localhost:8080/thinkphp-ozgweb/Public/simple/startbootstrap-sb-admin-2-gh-pages/bower_components/bootstrap/dist/js/bootstrap.min",
		"metisMenu": "http://localhost:8080/thinkphp-ozgweb/Public/simple/startbootstrap-sb-admin-2-gh-pages/bower_components/metisMenu/dist/metisMenu.min",		
		"sb_admin_2": "http://localhost:8080/thinkphp-ozgweb/Public/simple/startbootstrap-sb-admin-2-gh-pages/dist/js/sb-admin-2",
		"ckeditor": "http://localhost:8080/thinkphp-ozgweb/Public/simple/ckeditor/ckeditor",
		"ckeditor_jquery": "http://localhost:8080/thinkphp-ozgweb/Public/simple/ckeditor/adapters/jquery",
		"md5": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/md5",
		"utility": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/utility",
		"common": "http://localhost:8080/thinkphp-ozgweb/Public/simple/js/common"
    },
	shim: {
		"jquery_ui": [ "jquery" ],
		"jquery_validate": [ "jquery" ],
		"jquery_LinkageList": [ "jquery" ],
		"bootstrap": [ "jquery" ],
		"metisMenu": [ "jquery", "bootstrap" ],
		"sb_admin_2": [ "jquery", "metisMenu" ],
		"ckeditor_jquery": [ "jquery", "ckeditor" ],
		"common": [ "jquery", "jquery_ui" ]
	}
});
