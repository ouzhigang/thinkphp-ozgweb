require("../../less/art_single/get.less")
var utility = require("../utility.js");

$(function() {
			
	$('#content').ckeditor({ 
		height: '450px' 
	});
	$("#btn_submit").click(function() {
		
		var data = {
			id: $("#id").val(),
			content: CKEDITOR.instances.content.getData()
		};
		
		$.ajax({
			url: cfg.web_root + "mgr/art_single/get",
			type: "post",
			dataType: "json",
			data: data,
			beforeSend: function() {
				$("#btn_submit").attr("disabled", true);
			},
			success: function(res, status) {
				
				$("#dialog_alert").find(".modal-body").html(res.msg);
				$("#dialog_alert").modal('show');
				
			},
			complete: function() {
				$("#btn_submit").attr("disabled", false);
			}
		});
		
		return false;
	});
});

require("../common.js");
