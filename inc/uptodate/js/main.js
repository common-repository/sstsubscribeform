jQuery(document).ready(function($){
	$(".sstssfbgetupdate, .sstssfbtwitter, .sstssfbfacebook, .sstssbnevershowupdatenote").tipsy({gravity: 's', live: true});

	$(".sstssfbgetupdate").on("click touch", function(){
		$("#sstssfb_getupdatebysocialmedia, #sstssfbstarttour_block").fadeIn();
	});
	$(".sstssfbcloseupdate").on("click touch", function(){
		$("#sstssfb_getupdatebysocialmedia, #sstssfbstarttour_block").fadeOut();
		if($("#sstssbnevershowupdatenote").prop("checked") == true) {
			var data = {
					action: "nevershow_notification",
					nonce: Notify.SstssfbNotifyAdmin,
					hide : "confirm"
				};
				
			$.post(ajaxurl, data, function(response){
				if(response == 1) {
					$(".sstssfbgetupdate, #sstssfb_getupdatebysocialmedia").fadeOut(200, function(){
						$(this).remove();
					});
				} else {
					alert("Something went wrong when saving your preference! Please try again!");
				}
			});
			
			return;
		}		
	});
});