
require(
	[ "config" ], 
	function () {
		require([ "admin.data_add" ]);
	}), 
	define("admin.data_add", [ "jquery", "jquery_ui", "jquery_validate", "jquery_LinkageList", "bootstrap", "metisMenu", "sb_admin_2", "ckeditor", "ckeditor_jquery", "utility", "common" ], function ($) {
		page_init();
		
		//dialog
		var alert_dialog = ready_alert_dialog();
		
		$('#content').ckeditor({ 
			height: '350px' 
		});
				
		//无限级下拉框
		var data = {
			type: getUrlParam("type")		
			
		};
		$.ajax({
			url: "dataclass_gettree",
			type: "get",
			dataType: "json",
			data: data,
			beforeSend: function() {
				
			},
			success: function(res, status) {
		
				$("#dataclass").LinkageList(res.data, {
					objId: "dataclass",
					inputObjId: "dataclass_id",
					css: "form-control",
					style: "width: 20%; margin-right: 10px;",
					selectedValue: $("#dataclass_selected").val() == "0" ? null : $("#dataclass_selected").val()
				});
				
			},
			complete: function() {
				
			}
		});
		
		$("#main_form").validate({
			rules: {
				name: {
					required: true
				},
				sort: {
					required: true,
					digits: true
				}
			},
			messages: {
				name: {
					required: "没有填写名称"
				},
				sort: {
					required: "没有填写排序",
					number: "请输入整数"
				}				
			},
			submitHandler: function(form) {
				
				if($("#dataclass_id").val() == "0" || $("#dataclass_id").val() == "") {
					$("#dialog_message").html("没有选择分类");
					alert_dialog.dialog("open");
					return false;
				}
				
				var data = {
					id: $("#id").val(),
					name: $("#name").val(),
					sort: $("#sort").val(),
					dataclass_id: $("#dataclass_id").val(),
					content: $("#content").val(),
					type: $("#type").val()
				};
				$.ajax({
					url: "data_add",
					type: "post",
					dataType: "json",
					data: data,
					beforeSend: function() {
						$("#btn_submit").attr("disabled", true);
					},
					success: function(res, status) {
						if(res.code == 0) {
							
							location.href = "data_list?type=" + $("#type").val();
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
