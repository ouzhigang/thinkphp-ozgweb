require("../../less/feedback/show.less")
var utility = require("../utility.js");

var curr_page = 1;
var page_count = 1;

function show_data(page) {
	
	var data = {
		get_data: 1,
		page: page
	};	
	$.ajax({
		url: cfg.web_root + "mgr/feedback/show",
		type: "get",
		dataType: "json",
		data: data,
		beforeSend: function() {
			
		},
		success: function(res, status) {
			if(res.code == 0) {
				
				$("#datalist > tbody").empty();
				if(res.data.list) {
					for(var i = 0; i < res.data.list.length; i++) {				
						var row = "<tr>";
						row += "<td>" + res.data.list[i].id + "</td>"
						row += "<td>" + res.data.list[i].content + "</td>";
						row += "<td>" + res.data.list[i].add_time + "</td>";
						row += "<td><button class=\"btn btn-outline btn-link btn-xs\" type=\"button\" id=\"id_" + res.data.list[i].id + "\" data-id=\"" + res.data.list[i].id + "\">删除</button></td>";
						row += "</tr>";
						
						$("#datalist > tbody").append(row);
					}
				}
								
				page = res.data.page;
				page_count = res.data.page_count;				
				$("#page").html(page);
				$("#page_count").html(page_count);
				
				//点击删除
				$("button[id ^= 'id_']").click(function() {					
					var curr_obj = $(this);			
					var data = {
						id: curr_obj.attr("data-id")
					};
					$("#dialog_confirm").find(".modal-body").html("确定删除吗？");
					$("#dialog_confirm").modal('show');
					$("#dialog_confirm_btn").unbind().click(function() {
						
						$.ajax({
							url: cfg.web_root + "mgr/feedback/del",
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
		
});

require("../common.js");
