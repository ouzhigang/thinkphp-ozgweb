
var utility = require("./utility.js");

$(function() {
	
	$("#btn_set_home").click(function() {
		utility.SetHome(this,window.location);
		return false;
	});
	$("#add_favorite").click(function() {
		utility.AddFavorite(window.location, document.title);
		return false;
	});
	
	$("#btn_search").click(function() {
		if($("#text_search").val() == "") {
			alert("请输入搜索关键字");
		}
		else {			
			location.href = "productList?search=" + encodeURI($("#text_search").val());
			
		}
		
	});
	
});
