require("../../less/index/login.less")
var utility = require("../utility.js");

function submit_login(exist_vcode) {
	
	var message = "";
			
	if($("#name").val() == "")
		message += "用户名不能为空<br />";
	if($("#pwd").val() == "")
		message += "密码不能为空<br />";
	
	if(message == "") {
		var remember = 0;
		if($("#remember").is(":checked"))
			remember = 1;
		
		var data = {
			name: $("#name").val(),
			pwd: hex_md5($("#pwd").val()),
			remember: remember
		};
		
		if(exist_vcode)
			data.vcode = $("#vcode").val();
		
		$.ajax({
			type: "POST",
			url: cfg.web_root + "mgr/index/login",
			data: data,
			dataType: "json",
			beforeSend: function() {
				$("#btn_submit").html("登录中...");
				do_submit = false;
			},
			success: function(res) {
				if(res.code == 0) {
					//登录成功
					location.href = cfg.web_root + "mgr/other/main";
				}
				else if(res.code == 2) {
					//验证码
					$("#dialog_form").modal('show');
				}
				else {
					$("#dialog_form").modal('hide');
					$("#dialog_alert").find(".modal-body").html(res.msg);
					$("#dialog_alert").modal('show');
				}
				do_submit = true;
			},
			complete: function() {
				$("#btn_submit").html("登录");
				do_submit = true;
			}
		});
	}
	else {
		$("#dialog_alert").find(".modal-body").html(message);
		$("#dialog_alert").modal('show');
	}
}

$(function() {
	
	$("#dialog_form_btn").click(function() {
		//需要验证码登录
		submit_login(true);
	});
	$("#vcode_img").click(function() {
		$(this).attr("src", cfg.web_root + "mgr/index/getvcode?dt=" + Math.random());
	});
	
	var do_submit = true;
	$("#btn_submit").click(function() {
		//直接登录
		submit_login();
	});
	
	$("#pwd").keydown(function(e) {
		var curKey = e.which;
		if(curKey == 13) {
			$("#btn_submit").click();
			return false;
		}
	});
	
});

require("../common.js");
