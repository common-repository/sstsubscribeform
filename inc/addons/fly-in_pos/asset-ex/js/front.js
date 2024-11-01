/*
*** This script handle fly-in positioning. ***
*/

jQuery(document).ready(function($){	
	$(".sstssfb_main_wrapper").each(function(i, v) {
		var position = $(v).attr("data-pos");
		position = position != null ? position : "";
		if(position == "fly-in") {
			$(v).addClass("sstssbflyin_hide");
			var thewidth = "-" + $(v).outerWidth() + "px";
			$(v).css("right", thewidth);
 				$(window).on("scroll touchmove", function(e){
				 if(!$(v).hasClass("usersintentionhidden")) {
					if($(".sstssfbendofpost").length == 1) {
					if($(window).scrollTop() >= $(".sstssfbendofpost").offset().top + $(".sstssfbendofpost").outerHeight() + $(v).outerHeight() - window.innerHeight) {
						$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
					} else if($(window).scrollTop() < $(".sstssfbendofpost").offset().top + $(".sstssfbendofpost").outerHeight() + $(v).outerHeight() - window.innerHeight) {
						$(v).stop().animate({right: thewidth, "z-index": 999999, "margin-right": "0"}, 100);
					}
					} else if($(".sstssfbendofpost").length > 1) {
					if($(window).scrollTop() >= $(".sstssfbendofpost").last().offset().top + $(".sstssfbendofpost").last().outerHeight() + $(v).outerHeight() - window.innerHeight) {
						$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
					} else if($(window).scrollTop() < $(".sstssfbendofpost").last().offset().top + $(".sstssfbendofpost").last().outerHeight() + $(v).outerHeight() - window.innerHeight) {
						$(v).stop().animate({right: thewidth, "z-index": 999999, "margin-right": "0"}, 100);
					}
					} else if($(".sstssfbendofpost").length < 1 && $(document).height() > $(window).height() && $(document).height() - $(window).height() >= 100) {
						if($(window).scrollTop() >= 100) {
							$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
						} else if($(window).scrollTop() < 100) {
							$(v).stop().animate({right: thewidth, "z-index": 999999, "margin-right": "0"}, 100);
						}
					} else if($(".sstssfbendofpost").length < 1 && $(document).height() > $(window).height() && $(document).height() - $(window).height() <= 100 && $(document).height() - $(window).height() >= 1) {
						if($(window).scrollTop() >= 1) {
							$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
						} else if($(window).scrollTop() < 1) {
							$(v).stop().animate({right: thewidth, "z-index": 999999, "margin-right": "0"}, 100);
						}						
					}
				 }
				});				
				
				if($(".sstssfbendofpost").length < 1 && $(document).height() <= $(window).height()) {
					$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
				
				} else if($(document).height() <= $(window).height()) {
					$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
/* 					$(document).on("mouseenter touchstart", function(e) {
						$(v).stop().animate({right:0, "z-index": 999999, "margin-right": "5px"}, 100);
					});
					$(document).on("mouseleave touchend", function(e) {
					  if (e.pageY - $(document).scrollTop() <= 1) {
						$(v).stop().animate({right: thewidth, "z-index": 999999, "margin-right": "0"}, 100);
					  }
					});	 */				
				}			
		}
	});	
});