jQuery(document).ready(function($){

	function showhint(box, hint) {
		if(hint != "") {
		$(".info_text").html(hint);
			$(box).stop()
				  .css({"display": "table", "opacity": 0})
				  .show()
				  .fadeIn(1000)
				  .animate(
					{ opacity: 1 },
					{ queue: false, duration: 800 }
				  );
				return;
		}
		$("#sstssfb_position_hint").fadeOut(1000);
	}
	
	$("#sstssfb_position_hint .close").on("click touch", function(){
		$("#sstssfb_position_hint").stop().hide();
		return;
	});
	
	$("input[name='sstssfb_place[placement]']")
	.on("change", function(e){
		var classname = "." + $(this).val() + "_hints";
		var info = $(".hints_collections").find(classname).html();
		info = info != null ? info : "";		
		showhint("#sstssfb_position_hint", info);
	});
	
	$(".place-item .help").on("mouseover touchstart", function(){
		var classname = $(this).closest(".place-item").find("input[name='sstssfb_place[placement]']").val();
		classname = "." + classname + "_hints";		
		var info = $(".hints_collections").find(classname).html();
		info = info != null ? info : "";
		if(info != "") {
			$(".info_text").html(info);
			$("#sstssfb_position_hint").stop().css({"display": "table", "opacity": 1});
		}
	});

	$(".place-item .help").on("mouseleave touchend", function(){
		$("#sstssfb_position_hint").fadeOut(1000);
	});
	
	$("#sstssfb_position_hint").on("mouseover touchstart", function(){
		$(this).stop().show().fadeIn(100);
	});
});