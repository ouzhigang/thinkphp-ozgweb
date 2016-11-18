require("../../less/index/news_list.less");
var utility = require("../utility.js");

$(function() {
	
	function show_data() {
		
		do_show_data = false;
		var url = cfg.web_root + "site/index/getNewsList";
		$.getJSON(
			url,
			{
				"page": page
			},
			function(data) {
				if(data.code == 0) {
					
					var html = '';
					for(var i in data.data.list) {
						html += '<li>';
						html += '<img src="' + cfg.web_res_root + 'site/images/dot2.gif" />';
						html += '<a href="newsView?id=' + data.data.list[i].id + '" target="_blank">' + data.data.list[i].name + '</a>';
						html += '<span>[' + data.data.list[i].add_time + ']</span>';
						html += '</li>';
					}
					$("#news_list").append(html);
					
					$("#news_list > li").removeClass("last");
					$("#news_list > li").last().addClass("last");
					
					$("#news_list > li").unbind("mouseover").mouseover(function() {
						$(this).css("background-color", "#fefefe");
					});
					$("#news_list > li").unbind("mouseout").mouseout(function() {
						$(this).css("background-color", "transparent");
					});
					
					page = parseInt(data.data.page);
					page_count = parseInt(data.data.page_count);
					
					if(page < page_count) {
						page++;
						
						setTimeout(function() {
							do_show_data = true;
						}, 500);
					}					
				}
			}
		);
	}
	
	var page = 1;
	var page_count = 1;
	var do_show_data = true;	
	
	$(window).scroll(function() {
		if($(document).scrollTop() + $(window).height() >= $(document).height()) {
			
			if(do_show_data) {
				show_data();
			}
			
		}
	});
	
	show_data();
	
});
