jQuery(document).ready(function($){	
	$(".sstssfb_main_wrapper").each(function(i, v) {
		if($(this).attr("data-pos") != "fly-in" && $(this).attr("id").indexOf("sstssfb_main_wrapper") >= 0) {
			$(this).find(".sstssfbminimize, .sstssfbmaximizewrapper").remove()
					.end().find(".sstssfbcloseminimizewrapper").addClass("closeonly");
		}
		$(v).on("mouseover touchstart", function(){
			$(v).find(".sstssfbcloseminimizewrapper").show();
		});
		$(v).on("mouseleave touchend", function(){
			$(v).find(".sstssfbcloseminimizewrapper").hide();
		});
	});
	
	$(".sstssfbclose").on("click touch", function(){
		if($(this).closest(".sstssfb_main_wrapper").attr("id").indexOf("sstssfb_main_wrapper") < 0) {
			var the = $(this).closest(".sstssfb_main_wrapper");
				the.hide();
			setTimeout(function(){
				the.fadeIn(500);
			}, 1000);
			return;
		}
		$(this).closest(".sstssfb_main_wrapper").fadeOut(300, function(){
			$(this).remove();
		});
	});
	
	$(".sstssfbminimize").on("click touch", function(){	
		var thewidth = "-" + $(this).closest(".sstssfb_main_wrapper").outerWidth() + "px";
		$(this).closest(".sstssfb_main_wrapper")
			   .addClass("usersintentionhidden")
			   .animate(
				   {
					   right: thewidth,
					   "z-index": 999999,
					   "margin-right": "0"
				   },
				   {
					   queue: false,
					   duration: 300,
					   complete: function() {
							$(this).find(".sstssfbmaximizewrapper")
								   .show()
								   .animate(
								    {
									   left: "-20px"
								    },
								    300
								   );
							}
				   }
			   );
	});
	
	$(".sstssfbmaximize_right").on("click touch", function(){
		$(this).closest(".sstssfb_main_wrapper")			   
			   .removeClass("usersintentionhidden")
			   .stop()
			   .animate(
				{
				   right:0,
				   "z-index": 999999,
				   "margin-right": "5px"
				},
			   300,
			   function() {
					if($(this).attr("id").indexOf("sstssfb_main_wrapper") < 0) {
						var the = $(this);
						setTimeout(function(){
							the.fadeOut(500, function(){
								$(this).css({right: "",  "margin-right": ""});
									setTimeout(function(){
										the.fadeIn(500);
									}, 500);									   
							});
						}, 300);					
					}	
				}
			   )
			   .end()
			   .closest(".sstssfbmaximizewrapper")
			   .css({left: 0, "display": "none"});
	});
});