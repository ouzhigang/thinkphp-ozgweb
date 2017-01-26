var utility = require("./utility.js");

$(function() {
	
	//每个页面都执行一次这里
	
	//退出登录
	$("#btn_logout").click(function() {
		$("#dialog_confirm").find(".modal-body").html("确定退出吗？");
		$("#dialog_confirm").modal('show');
		$("#dialog_confirm_btn").unbind().click(function() {
			
			location.href = "../other/logout";
		});
		
	});
	
	jQuery.validator.addMethod("isMobile", function(value, element) {
		var length = value.length;
		var mobile = /^0?1[3|4|5|6|7|8][0-9]\d{8}$/;
		return this.optional(element) || (length == 11 && mobile.test(value));
	}, "请正确填写您的手机号码");
	
	//pjax
	$(document).pjax('.main-pjax-btn', '#page-wrapper', {
		fragment: '#page-wrapper'
	});
	
});
