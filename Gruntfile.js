module.exports = function(grunt) {
    grunt.initConfig({
        less: {
			options: {
				compress: true
			},
			compile: {
				files: {
					'public/static/simple/css/art_single/get.css': 'public/src/simple/less/art_single/get.less',
					'public/static/simple/css/data/add.css': 'public/src/simple/less/data/add.less',
					'public/static/simple/css/data/getlist.css': 'public/src/simple/less/data/getlist.less',
					'public/static/simple/css/dataclass/add.css': 'public/src/simple/less/dataclass/add.less',
					'public/static/simple/css/dataclass/getlist.css': 'public/src/simple/less/dataclass/getlist.less',
					'public/static/simple/css/friendlink/add.css': 'public/src/simple/less/friendlink/add.less',
					'public/static/simple/css/friendlink/getlist.css': 'public/src/simple/less/friendlink/getlist.less',
					'public/static/simple/css/feedback/getlist.css': 'public/src/simple/less/feedback/getlist.less',
					'public/static/simple/css/index/login.css': 'public/src/simple/less/index/login.less',
					'public/static/simple/css/other/main.css': 'public/src/simple/less/other/main.less',
					'public/static/simple/css/user/add.css': 'public/src/simple/less/user/add.less',
					'public/static/simple/css/user/updatepwd.css': 'public/src/simple/less/user/updatepwd.less',
					'public/static/simple/css/user/getlist.css': 'public/src/simple/less/user/getlist.less'
				}
			}
		}
    });

    grunt.loadNpmTasks('grunt-contrib-less');

	grunt.registerTask('default', ['less']);
	
};
