module.exports = function(grunt) {
    grunt.initConfig({
        less: {
			options: {
				compress: true
			},
			compile: {
				files: {
					'public/static/simple/css/art_single/get.css': 'public/static/simple/css/art_single/get.less',
					'public/static/simple/css/data/add.css': 'public/static/simple/css/data/add.less',
					'public/static/simple/css/dataclass/add.css': 'public/static/simple/css/dataclass/add.less',
					'public/static/simple/css/dataclass/getlist.css': 'public/static/simple/css/dataclass/getlist.less',
					'public/static/simple/css/friendlink/add.css': 'public/static/simple/css/friendlink/add.less',
					'public/static/simple/css/friendlink/getlist.css': 'public/static/simple/css/friendlink/getlist.less',
					'public/static/simple/css/index/login.css': 'public/static/simple/css/index/login.less',
					'public/static/simple/css/other/main.css': 'public/static/simple/css/other/main.less',
					'public/static/simple/css/user/add.css': 'public/static/simple/css/user/add.less',
					'public/static/simple/css/user/updatepwd.css': 'public/static/simple/css/user/updatepwd.less'
				}
			}
		}
    });

    grunt.loadNpmTasks('grunt-contrib-less');

	grunt.registerTask('default', ['less']);
	
};
