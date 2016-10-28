require("../../less/friendlink/getlist.less")
var utility = require("../utility.js");

$(function() {
		
	//dialog
	var alert_dialog = utility.ready_alert_dialog();
	
	$("#btnadd").click(function() {
		location.href = cfg.web_root + "simple/friendlink/add";
	});
	$("button[id ^= 'btnedit_']").click(function() {
		location.href = cfg.web_root + "simple/friendlink/add?" + $(this).attr("req-data");
	});
	$("button[id ^= 'btndel_']").click(function() {
		var btndel = $(this);
		var conform_dialog = utility.ready_confirm_dialog(function() {
			$.ajax({
				url: cfg.web_root + "simple/friendlink/del",
				type: "get",
				dataType: "json",
				data: btndel.attr("req-data"),
				beforeSend: function() {
					btndel.attr("disabled", true);
				},
				success: function(res, status) {
					if(res.code == 0) {
						
						btndel.parent().parent().remove();
					}
					else {							
						$("#dialog_message").html(res.msg);
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
	
});

require("../common.js");
