
var depth = 0;
function show_data(data) {
	
	if(data) {
		for(var i = 0; i < data.length; i++) {
			depth += 1;
			
			var item = "<li style=\"padding-left: " + (depth * 15) + "px\">";
			item += "<span class=\"fa fa-angle-right\"></span> " + data[i].name;
			item += "<div>";
			item += "<button type=\"button\" id=\"btn_edit_" + data[i].id + "\" class=\"btn btn-link\" req-data=\"dataclass_add?type=" + data[i].type + "&id=" + data[i].id + "\">编辑</button>";
			item += "<button type=\"button\" id=\"btn_del_" + data[i].id + "\" class=\"btn btn-link\" req-data=\"dataclass_del?id=" + data[i].id + "\">删除</button>";
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
		url: "dataclass_list",
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
					item += "<button type=\"button\" id=\"btn_edit_" + res.data[i].id + "\" class=\"btn btn-link\" req-data=\"dataclass_add?type=" + res.data[i].type + "&id=" + res.data[i].id + "\">编辑</button>";
					item += "<button type=\"button\" id=\"btn_del_" + res.data[i].id + "\" class=\"btn btn-link\" req-data=\"dataclass_del?id=" + res.data[i].id + "\">删除</button>";
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
					location.href = $(this).attr("req-data");
				});
				$("button[id ^= 'btn_del_']").click(function() {
					var btndel = $(this);
					
					var conform_dialog = ready_confirm_dialog(function() {
						
						$.ajax({
							url: btndel.attr("req-data"),
							type: "get",
							dataType: "json",
							beforeSend: function() {
								btndel.attr("disabled", true);
							},
							success: function(res, status) {
								if(res.code == 0) {
									
									req_data();
								}
								else {							
									$("#dialog_message").html(res.desc);
									alert_dialog.dialog("open");
								}
							},
							complete: function() {
								conform_dialog.dialog("close");
							}
						});
					});
					$("#dialog_message").html("确定删除吗？");
					conform_dialog.dialog("open");
					conform_dialog.parent().prev().css('z-index', 9998);
					conform_dialog.parent().css('z-index', 9999);
				});
			}				
		},
		complete: function() {
			
		}
	});
}

require(
	[ "config" ], 
	function () {
		require([ "admin.dataclass_list" ]);
	}), 
	define("admin.dataclass_list", [ "jquery", "jquery_ui", "bootstrap", "metisMenu", "sb_admin_2", "md5", "common" ], function ($) {
		page_init();
		
		req_data();
		
	}
);
