
require(
	[ "config" ], 
	function () {
		require([ "admin.art_single_get" ]);
	}), 
	define("admin.art_single_get", [ "jquery", "jquery_ui", "bootstrap", "metisMenu", "sb_admin_2", "ckeditor", "ckeditor_jquery", "common" ], function ($) {
		page_init();
		
		//dialog
		var alert_dialog = ready_alert_dialog();
				
		$('#content').ckeditor({ 
			height: '450px' 
		});
		$("#btn_submit").click(function() {
			
			var data = {
				id: $("#id").val(),
				content: CKEDITOR.instances.content.getData()
			};
			
			$.ajax({
				url: "../../art_single_get",
				type: "post",
				dataType: "json",
				data: data,
				beforeSend: function() {
					$("#btn_submit").attr("disabled", true);
				},
				success: function(res, status) {
					
					$("#dialog_message").html(res.desc);
					alert_dialog.dialog("open");
				},
				complete: function() {
					$("#btn_submit").attr("disabled", false);
				}
			});
			
		});
	}
);
