require("../../less/data_class/show.less")
var utility = require("../utility.js");

var depth = 0;
function show_data(data) {
	
	if(data) {
		for(var i = 0; i < data.length; i++) {
			depth += 1;
			
			var item = "<li style=\"padding-left: " + (depth * 15) + "px\">";
			item += "<span class=\"fa fa-angle-right\"></span> " + data[i].name;
			item += "<div>";
			item += "<button type=\"button\" id=\"btn_edit_" + data[i].id + "\" class=\"btn btn-link\" data-id=\"" + data[i].id + "\">编辑</button>";
			item += "<button type=\"button\" id=\"btn_del_" + data[i].id + "\" class=\"btn btn-link\" data-id=\"" + data[i].id + "\">删除</button>";
			item += "</div>";
			item += "</li>";
			
			$("#datalist").append(item);
						
			show_data(data[i].children);
			depth -= 1;
		}
	}
		
}

function req_data() {
	var data = {
		get_data: 1,
		type: $("#type").val()
	};
	
	$.ajax({
		url: cfg.web_root + "simple/data_class/show",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {
			
			if(res.code == 0) {
				$("#datalist").empty();
				for(var i = 0; i < res.data.length; i++) {
					
					var item = "<li>";
					item += "<span class=\"fa fa-angle-right\"></span> " + res.data[i].name;
					item += "<div>";
					item += "<button type=\"button\" id=\"btn_edit_" + res.data[i].id + "\" class=\"btn btn-link\" data-id=\"" + res.data[i].id + "\">编辑</button>";
					item += "<button type=\"button\" id=\"btn_del_" + res.data[i].id + "\" class=\"btn btn-link\" data-id=\"" + res.data[i].id + "\">删除</button>";
					item += "</div>";
					item += "</li>";
					
					$("#datalist").append(item);
					
					show_data(res.data[i].children);
				}
				
				$(".list-unstyled > li").mouseover(function() {			
					$(this).css("background-color", "#F5F5F0");
				});
				$(".list-unstyled > li").mouseout(function() {			
					$(this).css("background-color", "#FFFFFF");
				});
				
				//编辑删除按钮的时间
				$("button[id ^= 'btn_edit_']").click(function() {
					var data = {
						id: $(this).attr("data-id")
					};
					$.get(cfg.web_root + "simple/data_class/get", data, function(res) {
						if(res.code == 0) {
							$("#name").val(res.data.name);
							$("#sort").val(res.data.sort);
							$("#parent_selected").val(res.data.parent_id);
							$("#parent").empty();
							$("#id").val(res.data.id);
							
							$("#btn_submit").val("更新");
							
							req_parent();
							
							$("#add_table").show();
							$("#getlist_table").hide();							
						}
					});
				});
				$("button[id ^= 'btn_del_']").click(function() {
					var curr_obj = $(this);			
					var data = {
						id: curr_obj.attr("data-id")
					};
					$("#dialog_confirm").find(".modal-body").html("确定删除吗？");
					$("#dialog_confirm").modal('show');
					$("#dialog_confirm_btn").unbind().click(function() {
						
						$.ajax({
							url: cfg.web_root + "simple/data_class/del",
							type: "get",
							dataType: "json",
							data: data,
							beforeSend: function() {
								curr_obj.attr("disabled", true);
							},
							success: function(res, status) {
								$("#dialog_confirm").modal("hide");						
								if(res.code == 0) {
									req_data();
								}
								else {
									$("#dialog_alert").find(".modal-body").html(res.msg);
									$("#dialog_alert").modal("show");		
								}
							},
							complete: function() {
								curr_obj.attr("disabled", false);
							}
						});
						
					});
				});
			}				
		},
		complete: function() {
			
		}
	});
}

//无限级下拉框
function req_parent() {
	
	var data = {
		type: $("#type").val()
		
	};
	$.ajax({
		url: cfg.web_root + "simple/data_class/gettree",
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
}

$(function() {
	
	req_data();
	
	req_parent();
	
	$("#btn_add").click(function() {
		//初始化表单		
		$("#name").val("");
		$("#sort").val("0");
		$("#parent_selected").val("0");
		$("#parent").empty();
		$("#id").val("0");
		
		req_parent();
		
		$("#btn_submit").val("添加");
		
		$("#add_table").show();
		$("#getlist_table").hide();
	});
	$("#btn_getlist").click(function() {
		$("#add_table").hide();
		$("#getlist_table").show();
		
		req_data();
	});
	$("#add_table").hide();
	
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
				url: cfg.web_root + "simple/data_class/add",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					if(res.code == 0) {
						$("#btn_getlist").get(0).click();
					}
					else {
						$("#dialog_alert").find(".modal-body").html(res.msg);
						$("#dialog_alert").modal("show");		
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
