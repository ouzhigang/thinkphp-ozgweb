require("../../less/friendlink/show.less")
var utility = require("../utility.js");

var curr_page = 1;
var page_count = 1;

function show_data(page) {
	
	var data = {
		get_data: 1,
		page: page
	};
	
	$.ajax({
		url: cfg.web_root + "simple/friendlink/show",
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
                    row += '<td>';
					row += '<h5>' + res.data.list[i].id + '</h5>';
					row += '</td>';
                    row += '<td>';
					row += '<h5>' + res.data.list[i].name + '</h5>';
					row += '</td>';
                    row += '<td>';
					row += '<h5>' + res.data.list[i].url + '</h5>';
					row += '</td>';
					row += '<td>';
					
					if(res.data.list[i].is_picture == 1) {
						row += '<img src="' + res.data.list[i].picture + '" width="88px" height="31px" />';		
					}
					else {
						row += '无';
					}
					
					row += '</td>';
                    row += '<td>';
					row += '<button type="button" class="btn btn-link btn-xs" data-id="' + res.data.list[i].id + '" id="btn_edit_' + res.data.list[i].id + '">编辑</button>';
					row += '<button type="button" class="btn btn-link btn-xs" data-id="' + res.data.list[i].id + '" id="btn_del_' + res.data.list[i].id + '">删除</button>';
					row += '</td>';
					row += "</tr>";
					
					$("#datalist > tbody").append(row);
				}
				
				page = res.data.page;
				page_count = res.data.page_count;				
				$("#page").html(page);
				$("#page_count").html(page_count);
				
				$("button[id ^= 'btn_edit_']").click(function() {
					var data = {
						id: $(this).attr("data-id")
					};
					$.get(cfg.web_root + "simple/friendlink/get", data, function(res) {
						if(res.code == 0) {
							
							$("#name").val(res.data.name);
							$("#sort").val(res.data.sort);
							$("#url").val(res.data.url);
							if(res.data.is_picture == 0)
								$("#is_picture_0").attr("checked", "checked");
							else
								$("#is_picture_1").attr("checked", "checked");
							$("#picture").val(res.data.picture);
							$("#id").val(res.data.id);
							
							show_picture_row($("input[name = 'is_picture']:checked").val());
							
							$("#btn_submit").val("更新");
							
							$("#add_table").show();
							$("#getlist_table").hide();							
						}
					});
				});
				
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
							url: cfg.web_root + "simple/friendlink/del",
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

function show_picture_row(is_picture_val) {
	
	if(is_picture_val == 1) {
		$("#picture").parent().parent().show();
	}
	else {
		$("#picture").parent().parent().hide();
	}
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
		$("#sort").val("0");
		$("#url").val("");
		$("#is_picture_1").attr("checked", "checked");
		$("#picture").val("");
		$("#id").val("0");
		
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
	
	$("input[name='is_picture']").change(function() {
		show_picture_row($(this).val());
	});
	show_picture_row($("input[name = 'is_picture']:checked").val());
	
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
				url: cfg.web_root + "simple/friendlink/add",
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
