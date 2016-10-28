require("../../less/user/add.less")
var utility = require("../utility.js");

var alert_dialog = null;

$(function() {
		
	//dialog
	alert_dialog = utility.ready_alert_dialog();
	
	$("#main_form").validate({
		rules: {
			name: {
				required: true,
				minlength: 5
			},
			pwd: {
				required: true,
				minlength: 5,
				equalTo: "#pwd2"
			}
		},
		messages: {
			name: {
				required: "没有填写用户名",
				minlength: $.validator.format("用户名不能小于{0}个字符")
			},
			pwd: {
				required: "没有填写密码",
				minlength: "密码不能小于{0}个字符",
				equalTo: "两次输入密码不一致"
			}
		},
		submitHandler: function(form) {
			
			var data = {
				name: $("#name").val(),
				pwd: hex_md5($("#pwd").val()),
				pwd2: hex_md5($("#pwd2").val())
			};
			
			$.ajax({
				url: cfg.web_root + "simple/user/add",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					if(res.code == 0) {
						
						location.href = cfg.web_root + "simple/user/getlist";
					}
					else {
						$("#dialog_message").html(res.msg);
						alert_dialog.dialog("open");
					}
				},
				complete: function() {
					$("#btn_submit").attr("disabled", false);
				}
			});
		}
	});
		
});

require("../common.js");
