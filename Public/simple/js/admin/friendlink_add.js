
function show_picture_row(is_picture_val) {
	
	if(is_picture_val == 1) {
		$("#picture").parent().parent().show();
	}
	else {
		$("#picture").parent().parent().hide();
	}
}

require(
	[ "config" ], 
	function () {
		require([ "admin.friendlink_add" ]);
	}), 
	define("admin.friendlink_add", [ "jquery", "jquery_ui", "jquery_validate", "bootstrap", "metisMenu", "sb_admin_2", "md5", "common" ], function ($) {		
		page_init();
		
		//dialog
		var alert_dialog = ready_alert_dialog();
		
		$("input[name='is_picture']").change(function() {
			show_picture_row($(this).val());
		});
		show_picture_row($("input[name='is_picture']:checked").val());
		
		$("#main_form").validate({
			rules: {
				name: {
					required: true
				},
				sort: {
					required: true,
					digits: true
				},
				url: {
					required: true,
					url:true
				},
				picture: {
					url:true
				}
			},
			messages: {
				name: {
					required: "没有填写名称"
				},
				sort: {
					required: "没有填写排序",
					number: "请输入整数"
				},
				url: {
					required: "没有填写URL",
					url: "没有填写正确的URL"
				},
				picture: {
					url: "没有填写正确的URL"
				}
			},
			submitHandler: function(form) {
				
				var data = {
					id: $("#id").val(),
					name: $("#name").val(),
					sort: $("#sort").val(),
					url: $("#url").val(),
					is_picture: $("input[name = 'is_picture']:checked").val(),
					picture: $("#picture").val(),
				};
				
				$.ajax({
					url: "friendlink_add",
					type: "post",
					dataType: "json",
					data: data,
					beforeSend: function() {
						$("#btn_submit").attr("disabled", true);
					},
					success: function(res, status) {
						if(res.code == 0) {
							
							location.href = "friendlink";
						}
						else {
							$("#dialog_message").html(res.desc);
							alert_dialog.dialog("open");
						}
					},
					complete: function() {
						$("#btn_submit").attr("disabled", false);
					}
				});
				
			}
		});
		
	}
);
