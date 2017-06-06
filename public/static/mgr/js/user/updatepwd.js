
$(function() {
	
	$("#main_form").validate({
		rules: {
			old_pwd: {
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
			old_pwd: {
				required: "没有填写旧密码",
				minlength: $.validator.format("旧密码不能小于{0}个字符")
			},
			pwd: {
				required: "没有填写新密码",
				minlength: "新密码不能小于{0}个字符",
				equalTo: "两次输入密码不一致"
			}
		},
		submitHandler: function(form) {
							
			var data = {
				old_pwd: hex_md5($("#old_pwd").val()),
				pwd: hex_md5($("#pwd").val()),
				pwd2: hex_md5($("#pwd2").val())					
			};
			
			$.ajax({
				url: cfg.web_root + "mgr/user/updatepwd",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					
					$("#dialog_alert").find(".modal-body").html(res.msg);
					$("#dialog_alert").modal("show");
				},
				complete: function() {
					$("#btn_submit").attr("disabled", false);
					$("#main_form").get(0).reset();
				}
			});
			
			return false;
		}
	});
	
});
