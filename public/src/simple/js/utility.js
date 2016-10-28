
module.exports.ready_alert_dialog = function() {
	var dialog = $("#dialog_div").dialog({
		modal: true,
		autoOpen:false,
		buttons: {
			"关闭": function() {
				$(this).dialog("close");
			}
		}
	});
	
	return dialog;
};

module.exports.ready_confirm_dialog = function(sureCallBack) {
	var dialog = $("#dialog_div").dialog({
		modal: true,
		autoOpen:false,
		buttons: {
			"关闭": function() {
				$(this).dialog("close");
			},
			"确定": sureCallBack
		}
	});
	
	return dialog;
};
