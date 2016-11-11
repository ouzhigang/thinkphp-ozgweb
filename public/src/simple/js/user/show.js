require("../../less/user/show.less")
var utility = require("../utility.js");

var curr_page = 1;
var page_count = 1;

function show_data(page) {
	
	var data = {
		get_data: 1,
		page: page
	};
	
	$.ajax({
		url: cfg.web_root + "simple/user/show",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {
			if(res.code == 0) {
				
				$("#datalist > tbody").empty();
				for(var i = 0; i < res.data.list.length; i++) {				
					var row = "<tr>";
					row += "<td>" + res.data.list[i].id + "</td>"
					row += "<td>" + res.data.list[i].name + "</td>";
					row += "<td>" + res.data.list[i].add_time + "</td>";
					row += "<td>";
					row += "<button class=\"btn btn-outline btn-link btn-xs\" type=\"button\" id=\"btn_del_" + res.data.list[i].id + "\" data-id=\"" + res.data.list[i].id + "\">删除</button>";
					row += "</td>";
					row += "</tr>";
					
					$("#datalist > tbody").append(row);
				}
				
				page = res.data.page;
				page_count = res.data.page_count;				
				$("#page").html(page);
				$("#page_count").html(page_count);
				
				//点击删除
				$("button[id ^= 'btn_del_']").click(function() {
					var curr_obj = $(this);			
					var data = {
						id: curr_obj.attr("data-id")
					};
					$("#dialog_confirm").find(".modal-body").html("确定删除吗？");
					$("#dialog_confirm").modal('show');
					$("#dialog_confirm_btn").unbind().click(function() {
						
						$.ajax({
							url: cfg.web_root + "simple/user/del",
							type: "get",
							dataType: "json",
							data: data,
							beforeSend: function() {
								curr_obj.attr("disabled", true);
							},
							success: function(res, status) {
								$("#dialog_confirm").modal("hide");						
								if(res.code == 0) {
									
									show_data(curr_page);
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

$(function() {
	
	show_data(curr_page);		
	$("#page_first").click(function() {
		curr_page = 1;		
		show_data(curr_page);	
	});
	$("#page_prev").click(function() {
		curr_page--;
		if(curr_page < 1)
			curr_page = 1;
		show_data(curr_page);	
	});
	$("#page_next").click(function() {
		curr_page++;
		if(curr_page > page_count)
			curr_page = page_count;
		show_data(curr_page);
	});
	$("#page_last").click(function() {
		curr_page = page_count;
		show_data(page_count);	
	});
	
	$("#btn_add").click(function() {
		//初始化表单		
		$("#name").val("");
		$("#pwd").val("");
		$("#pwd2").val("");
		
		$("#btn_submit").val("添加");
		
		$("#add_table").show();
		$("#getlist_table").hide();
	});
	$("#btn_getlist").click(function() {
		$("#add_table").hide();
		$("#getlist_table").show();
		
		show_data(curr_page);
	});
	$("#add_table").hide();
	
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
				pwd: $("#pwd").val(),
				pwd2: $("#pwd2").val()
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
			
			return false;
		}
	});
	
});

require("../common.js");
