require("../../less/data/add.less")
var utility = require("../utility.js");

$(function() {
	
	//dialog
	var alert_dialog = utility.ready_alert_dialog();
	
	$('#content').ckeditor({ 
		height: '350px' 
	});
	
	//上传
	var btn_upload = null;
	$("input[id ^= 'btn_upload_']").click(function() {
		btn_upload = $(this);
		var id = $(this).attr("id").split("_");
		id = id[id.length - 1];
		$("#file_upload_" + id).click();
	});
	$("input[id ^= 'file_upload_']").fileupload({
		url: cfg.web_root + "simple/data/upload",
		dataType: 'json',
		done: function(e, data) {
			var id = btn_upload.attr("id").split("_");
			id = id[id.length - 1];
			
			if(data.result.code == 0) {
				$("#picture_" + id).val(data.result.data.filepath);
			}
			else {
				$("#dialog_message").html(data.result.msg);
				alert_dialog.dialog("open");
			}				
		},
		progressall: function (e, data) {
			//var progress = parseInt(data.loaded / data.total * 100, 10);
			
		}
	});
	
	$("input[id ^= 'file_upload_']").hide();	
	$("button.add").hide();
	$("button.add:last").show();
	if($("button.dec").length == 1)
		$("button.dec").hide();
	
	$("button.add").click(function() {
		var id = "0";
		do {
			id = parseInt(Math.random() * 1000000);
		}
		while($("#picture_" + id).length > 0);
		
		$(this).parent().after($(this).parent().clone(true));
		$(".picture_div").last().find("input[type = 'hidden']").val(id);
		$(".picture_div").last().find("input[id ^= 'picture_']").val("");
		$(".picture_div").last().find(".dec").show();
		
		$(".picture_div").last().find("input[id ^= 'picture_']").each(function() {
			var ids = $(this).attr("id").split("_");
			var tmp = "";
			for(var i = 0; i < ids.length - 1; i++) {
				tmp += ids[i] + "_";
			}
			tmp += id;
			$(this).attr("id", tmp);
			$(this).attr("name", tmp);
		});
		$(".picture_div").last().find("input[id ^= 'btn_upload_']").each(function() {
			var ids = $(this).attr("id").split("_");
			var tmp = "";
			for(var i = 0; i < ids.length - 1; i++) {
				tmp += ids[i] + "_";
			}
			tmp += id;
			$(this).attr("id", tmp);
		});
		$(".picture_div").last().find("input[id ^= 'file_upload_']").each(function() {
			var ids = $(this).attr("id").split("_");
			var tmp = "";
			for(var i = 0; i < ids.length - 1; i++) {
				tmp += ids[i] + "_";
			}
			tmp += id;
			$(this).attr("id", tmp);
			$(this).attr("name", tmp);
		});
		
		$("button.add").hide();
		$("button.add:last").show();
		$("button.dec").show();
	});
	$("button.dec").click(function() {
		$(this).parent().remove();
		
		$("button.add").hide();
		$("button.add:last").show();
		
		if($("button.dec").length == 1)
			$("button.dec").hide();
	});
	//上传 end
	
	//无限级下拉框
	var data = {
		type: getUrlParam("type")		
		
	};
	$.ajax({
		url: cfg.web_root + "simple/data_class/gettree",
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
				picture: [],
				type: $("#type").val()
			};
			
			$("input[id ^= 'picture_']").each(function() {
				if($(this).val() != "") {
					data.picture.push($(this).val());
				}
			});
			
			$.ajax({
				url: cfg.web_root + "simple/data/add",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					if(res.code == 0) {
						
						location.href = cfg.web_root + "simple/data/getlist?type=" + $("#type").val();
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
