var utility = require("./utility.js");

$(function() {
	
	//每个页面都执行一次这里
	
	//退出登录
	$("#btn_logout").click(function() {
		var conform_dialog = utility.ready_confirm_dialog(function() {
			
			location.href = "../other/logout";
		});
		$("#dialog_message").html("确定退出吗？");
		conform_dialog.dialog("open");
		conform_dialog.parent().prev().css('z-index', 9998);
		conform_dialog.parent().css('z-index', 9999);
	});
	
});
