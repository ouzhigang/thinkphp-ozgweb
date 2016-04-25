
$(function() {
	
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
		url: "../dataclass/gettree",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {
	
			$("#dataclass").LinkageList(res.data, {
				objId: "dataclass",
				inputObjId: "data_class_id",
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
			
			if($("#data_class_id").val() == "0" || $("#data_class_id").val() == "") {
				$("#dialog_message").html("没有选择分类");
				alert_dialog.dialog("open");
				return false;
			}
			
			var data = {
				id: $("#id").val(),
				name: $("#name").val(),
				sort: $("#sort").val(),
				data_class_id: $("#data_class_id").val(),
				content: $("#content").val(),
				type: $("#type").val()
			};
			$.ajax({
				url: "add",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					if(res.code == 0) {
						
						location.href = "getlist?type=" + $("#type").val();
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
	
});