
$(function() {
	
	//banner滚动
	$("#banner_slider").show();
	$('.bxslider').bxSlider({
		mode: 'fade',
		captions: true,
		auto: true,
		pause: 8000
	});
	$(".bx-controls-direction").hide();
	//banner滚动
	
	//产品滚动
	if($("#product_list1 .product-name").length > 6) {
		var speed = 30;
		var product_list = $("#product_list");
		var product_list1 = $("#product_list1");
		var product_list2 = $("#product_list2");
		product_list2.html(product_list1.html());
		
		function Marquee() { 
			if(product_list.scrollLeft() >= product_list1.width())
				product_list.scrollLeft(0); 
			else {
				product_list.scrollLeft(product_list.scrollLeft() + 1);
			}
		}
		var MyMar = setInterval(Marquee, speed);
		product_list.mouseover(function() {
			clearInterval(MyMar);
		});
		product_list.mouseout(function() {
			MyMar = setInterval(Marquee, speed);
		});
	}
	//产品滚动 end
	
});
