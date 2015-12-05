
var alert_dialog = null;
var form_dialog = null;
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
			url: "login",
			data: data,
			dataType: "json",
			beforeSend: function() {
				$("#btn_submit").html("登录中...");
				do_submit = false;
			},
			success: function(res) {
				if(res.code == 0) {
					//登录成功
					location.href = "main";
				}
				else if(res.code == 2) {							
					//验证码
					form_dialog.dialog("open");		
				}
				else {
					$("#dialog_message").html(res.desc);
					alert_dialog.dialog("open");
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
		
		$("#dialog_message").html(message);
		alert_dialog.dialog("open");
	}
}

require(
	[ "config" ], 
	function () {
		require([ "admin.login" ]);
	}), 
	define("admin.login", [ "jquery", "jquery_ui", "bootstrap", "metisMenu", "sb_admin_2", "md5", "common" ], function ($) {
		//dialog
		alert_dialog = ready_alert_dialog();
		form_dialog = $( "#dialog_form" ).dialog({
			autoOpen: false,
			width: 400,
			height: 200,			
			modal: true,
			buttons: {
				"登录": function() {
					//需要验证码登录
					submit_login(true);
				},
				"关闭": function() {
					$(this).dialog("close");
				}
			},
			open: function() {				
				$("#vcode_img").click(function() {
					$(this).attr("src", "getvcode?dt=" + Math.random());
				});
						
			},
			close: function() {
				
			}
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
	}
);