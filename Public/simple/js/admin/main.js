
require(
	[ "config" ], 
	function () {
		require([ "admin.main" ]);
	}), 
	define("admin.main", [ "jquery", "jquery_ui", "bootstrap", "metisMenu", "sb_admin_2", "md5", "common" ], function ($) {
		
		page_init();
	}
);
