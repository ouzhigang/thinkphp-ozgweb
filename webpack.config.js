
var entry_path = "./public/src/simple/js";
var output_path = "./public/static/simple/js";

module.exports = {
	entry: {
		"index/login": [ entry_path + "/index/login.js" ],
		"other/main": [ entry_path + "/other/main.js" ],
		"art_single/get": [ entry_path + "/art_single/get.js" ],
		"data/add": [ entry_path + "/data/add.js" ],
		"data/getlist": [ entry_path + "/data/getlist.js" ],
		"data_class/add": [ entry_path + "/data_class/add.js" ],
		"data_class/getlist": [ entry_path + "/data_class/getlist.js" ],
		"feedback/getlist": [ entry_path + "/feedback/getlist.js" ],
		"friendlink/add": [ entry_path + "/friendlink/add.js" ],
		"friendlink/getlist": [ entry_path + "/friendlink/getlist.js" ],
		"user/add": [ entry_path + "/user/add.js" ],
		"user/getlist": [ entry_path + "/user/getlist.js" ],
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
