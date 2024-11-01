jQuery(document).ready(function($){
	$(".sstssfb_main_wrapper").each(function(i, v){
		var childrenwidth = $(v).find("div:first").css("width");
		$(v).css("width", childrenwidth);
	});
});