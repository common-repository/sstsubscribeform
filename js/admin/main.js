jQuery(document).ready(function($){
	$("#sstssfb_tab").tabs();
	$("html").niceScroll({horizrailenabled:false});
	$(".submitdelete").tipsy({gravity: $.fn.tipsy.autoNS, live: true});

	$("#delete-action a")
	.addClass("dashicons dashicons-no").attr("title", "Delete")
	.removeAttr("href")
	.on("click touch", function(e){
	 e.preventDefault();
	 $("#sstssfb_ajax_loader").show();
	 var url = $("#sstssfb_delete_post_url").val();
	 var idnumber = $(".sstssb_shortcode_show_value").val();
		 idnumber = idnumber.match(/\d+/);
	 
			var data = {
				action: "delete_post",
				nonce : EmailService.SstssfbEmailServiceAdmin,
				id	  : idnumber 
			};
			
			$.post(ajaxurl, data, function(response) {
				if(response != "") {
					alert(response);
				}
				location.href = url; 
			});
	});
		
	
// keep this be the last line of codes in this file
	$("#sstssfb_tab").show();
});