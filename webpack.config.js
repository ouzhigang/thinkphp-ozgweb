
var entry_path = "./public/src/simple/js";
var output_path = "./public/static/simple/js";

module.exports = {
	entry: {
		"index/login": [ entry_path + "/index/login.js" ],
		"other/main": [ entry_path + "/other/main.js" ],
		"art_single/get": [ entry_path + "/art_single/get.js" ],
		"data/show": [ entry_path + "/data/show.js" ],
		"data_class/show": [ entry_path + "/data_class/show.js" ],
		"feedback/show": [ entry_path + "/feedback/show.js" ],
		"friendlink/show": [ entry_path + "/friendlink/show.js" ],
		"user/show": [ entry_path + "/user/show.js" ],
		"user/updatepwd": [ entry_path + "/user/updatepwd.js" ]
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
