
$(function() {
	page_init();
		
	//dialog
	var alert_dialog = ready_alert_dialog();
	
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
			
			var data = {
				id: $("#id").val(),
				name: $("#name").val(),
				sort: $("#sort").val(),
				parent_id: $("#parent_id").val(),
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
	
	//无限级下拉框
	var data = {
		type: getUrlParam("type")		
		
	};
	$.ajax({
		url: "gettree",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {				
			$("#parent").LinkageList(res.data, {
				objId: "parent",
				inputObjId: "parent_id",
				css: "form-control",
				style: "width: 20%; margin-right: 10px;",
				selectedValue: $("#parent_selected").val() == "0" ? null : $("#parent_selected").val()
			});
			
		},
		complete: function() {
			
		}
	});
	
});
