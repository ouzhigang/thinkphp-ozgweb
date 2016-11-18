var mgr_entry_path = "./public/src/mgr/js";
var site_entry_path = "./public/src/site/js";
var output_path = "./public/static";

module.exports = {
	entry: {
		//后台
		"mgr/js/index/login": [ mgr_entry_path + "/index/login.js" ],
		"mgr/js/other/main": [ mgr_entry_path + "/other/main.js" ],
		"mgr/js/art_single/get": [ mgr_entry_path + "/art_single/get.js" ],
		"mgr/js/data/show": [ mgr_entry_path + "/data/show.js" ],
		"mgr/js/data_class/show": [ mgr_entry_path + "/data_class/show.js" ],
		"mgr/js/feedback/show": [ mgr_entry_path + "/feedback/show.js" ],
		"mgr/js/friendlink/show": [ mgr_entry_path + "/friendlink/show.js" ],
		"mgr/js/user/show": [ mgr_entry_path + "/user/show.js" ],
		"mgr/js/user/updatepwd": [ mgr_entry_path + "/user/updatepwd.js" ],
		
		//前台
		"site/js/index/index": [ site_entry_path + "/index/index.js" ],
		"site/js/index/art": [ site_entry_path + "/index/art.js" ],
		"site/js/index/news_list": [ site_entry_path + "/index/news_list.js" ],
		"site/js/index/news_view": [ site_entry_path + "/index/news_view.js" ],
		"site/js/index/product_list": [ site_entry_path + "/index/product_list.js" ],
		"site/js/index/product_view": [ site_entry_path + "/index/news_view.js" ]
	},
	output: {
		path: output_path, // 输出文件的保存路径
		filename: "[name].js" // 输出文件的名称
	},	
	module: {
        loaders: [
            {
				test: /\.css$/,
				loader: "style!css"
			},
            {
				test: /\.less$/,
				loader: "style!css!less"
			},

        ]
    }
	
};
