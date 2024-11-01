jQuery(document).ready(function($){
	
	$(".warning").tipsy({gravity: $.fn.tipsy.autoNS, live: true});
	
	
			$("#mail_submit_button").on("click", function(e) {				
				e.preventDefault();
					var wrapper = $("#sstssfb_selectservice").find("option:selected").attr("data-id");
					if(wrapper == "sstssfb_mchimp") {
					var post_id = $("#sstssfb_selectservice").attr("data-id");				
						$(".mchimp_lists, #sstssfb_mchimp_result").hide();
						$("#error_notification").text("");
						$("#sstssfb_ajax_loader").show();
						var identity = $("#sstssfb_mchimp_name").val();
						var apikey = $("#sstssfb_mchimp_api_key").val();
						
						if(identity == "" || apikey == "") {
							// say something
							$("#error_notification").text("Please complete the fields!");
							$("#sstssfb_ajax_loader").hide();
							$(".cancel_new_account").on("click touch", function(){
								$(".mchimp_lists, #sstssfb_mchimp_result").show();
								$(".mail_service_option").hide();
								$("#error_notification").text("");
							});
							return;
						}
						
						var data = {
							action: "mchimp_admin",
							nonce: MchimpAdmin.SstssfbAdminAjax,
							id: identity,
							api: apikey,
							req_type: 'authentication',
							post_id : post_id
						};
						
						$.post(ajaxurl, data, function(response){
							$("#sstssfb_ajax_loader").hide();
						if(response != "invalid") {
							$(".mchimp_lists, #sstssfb_mchimp_result").remove();
							$(response).insertAfter(".submit_tr");
							$(".mchimp_row_hide").hide();
						} else {
							$("#error_notification").text("Connection failed! Please check your api key and or retry!");
							$("#sstssfb_mchimp_api_key").val("");					
						}
						});
					}
			});	
	
			$(".add_new_account").on("click", function() {				
			var wrapper = $("#sstssfb_selectservice").find("option:selected").attr("data-id");
			if(wrapper == "sstssfb_mchimp") {
			var post_id = $("#sstssfb_selectservice").attr("data-id");
			$("#mail_submit_button").on("click", function(e) {
				e.preventDefault();
				$(".mchimp_lists, #sstssfb_mchimp_result").hide();
				$("#error_notification").text("");
				$("#sstssfb_ajax_loader").show();
				var identity = $("#sstssfb_mchimp_name").val();
				var apikey = $("#sstssfb_mchimp_api_key").val();
				
				if(identity == "" || apikey == "") {
					// say something
					$("#error_notification").text("Please complete the fields!");
					$("#sstssfb_ajax_loader").hide();
					$(".cancel_new_account").on("click touch", function(){
						$(".mchimp_lists, #sstssfb_mchimp_result").show();
						$(".mail_service_option").hide();
						$("#error_notification").text("");
					});
					return;
				}
				
				var data = {
					action: "mchimp_admin",
					nonce: MchimpAdmin.SstssfbAdminAjax,
					id: identity,
					api: apikey,
					req_type: 'authentication',
					post_id : post_id
				};
				
				$.post(ajaxurl, data, function(response){
					$("#sstssfb_ajax_loader").hide();
				if(response != "invalid") {
					$(".mchimp_lists, #sstssfb_mchimp_result").remove();
					$(response).insertAfter(".submit_tr");
					$(".mchimp_row_hide").hide();
				} else {
					$("#error_notification").text("Connection failed! Please check your api key and or retry!");
					$("#sstssfb_mchimp_api_key").val("");					
				}
				});
			});	
			}
			});
	
	/* if selected service is mailchimp */
	var wrapper = $("#sstssfb_selectservice").find("option:selected").attr("data-id");
	if(wrapper == "sstssfb_mchimp") {
		if($("#sstssfb_mchimp_result").length) {
			$("#sstssfb_mchimp_result").show();
		} else {
			$("#sstssfb_mchimp").show();
			$(".cancel_new_account").hide();
		}
	}
	
	$("#sstssfb_selectservice").on("change", function(){ // required
	$("#error_notification").text("");
	var post_id = $(this).attr("data-id");
	 var wrapper = $(this).find("option:selected").attr("data-id");	 
	 /* check if the selected service is handled by current add on. */
	
		if(wrapper == "sstssfb_mchimp") {	
		$("#sstssfb_ajax_loader").show();
		
			if($(".mchimp_lists").length){
				$(".mchimp_lists, #sstssfb_mchimp, #sstssfb_mchimp_result").show();
				$("#sstssfb_ajax_loader, .mchimp_row_hide").hide();
				return;
			}
		// if guide cookie is set, then do this
		if($("#quickguidetourselected").val() == "yes") {
		var mchimpguide = {
						id: "mailchimp-tour",
						steps: [
							{
							  title: "Give a name",
							  content: "Give any name for your account!",
							  target: "#sstssfb_mchimp_name",
							  placement: "top",
							  arrowOffset: 0,
							  yOffset: "5",
							  onNext: function() {}
							},
							{
							  title: "Mailchimp API key",
							  content: "Supply your mailchimp api key into this field! Click the help link next to this field if you don't know how to obtain your api key!",
							  target: "#sstssfb_mchimp_api_key",
							  placement: "top",
							  arrowOffset: 0,
							  yOffset: "5",
							  onNext: function() {}
							},
							{
							  title: "Authenticate",
							  content: "Click this button then wait for the authentication of your api credential and generation of your mailchimp account's data is finished!",
							  target: "#mail_submit_button",
							  placement: "top",
							  arrowOffset: 0,
							  yOffset: "5",
							  onNext: function() {},
							  showNextButton: false,
							  nextOnTargetClick: true
							},
						],
						  /* bubblePadding: "8", */
						  showPrevButton: true,
						  showNextButton: true,
						  fixedElement: false,
						  onStart: function() {}						
			};
		} else {
			 $("#sstssfbstarttour").hide();
		}
		
		// check if post type exist and there is already saved account
				var data = {
					action: "mchimp_admin",
					nonce: MchimpAdmin.SstssfbAdminAjax,
					wrppr: wrapper, 
					req_type: 'retrieval',
					post_id : post_id
				};
				
				$.post(ajaxurl, data, function(response){ 
					$("#sstssfb_ajax_loader").hide();
					$("#sstssfbstarttour").hide();
					if($.trim(response) == "exist"){
						$(".mchimp_lists, #sstssfb_mchimp_result, .cancel_new_account").show();
						return;
					}
					if($.trim(response) != "no_exist") { // "multiple_email" post type exist
						if($.trim(response) != "false") { // there are mailchimp saved account 
							
						} else { // no mailchimp saved account in post type
							$("#" + wrapper).show();
							if(typeof mchimpguide != "undefined") {
								hopscotch.startTour(mchimpguide, 0);
							}
						}
					} else { // no "multiple_email" post type exist
						$("#" + wrapper).show();
						$(".cancel_new_account").hide();
						if(typeof mchimpguide != "undefined") {
							hopscotch.startTour(mchimpguide, 0);
						}
					}
				});
			// retrieve list	
			$("#mailchimp_select_name").on("change", function(){
				alert();
			});
		
			$("#mail_submit_button").on("click", function(e) {
				e.preventDefault();
				$(".hopscotch-bubble-close").trigger("click");
				$(".mchimp_lists, #sstssfb_mchimp_result").hide();
				$("#error_notification").text("");
				$("#sstssfb_ajax_loader").show();
				var identity = $("#sstssfb_mchimp_name").val();
				var apikey = $("#sstssfb_mchimp_api_key").val();
				
				if(identity == "" || apikey == "") {
					// say something
					$("#error_notification").text("Please complete the fields!");
					$("#sstssfb_ajax_loader").hide();
					$(".cancel_new_account").on("click touch", function(){
						$(".mchimp_lists, #sstssfb_mchimp_result").show();
						$(".mail_service_option").hide();
						$("#error_notification").text("");
					});
					return;
				}
	if($("#quickguidetourselected").val() == "yes") {
		// if guide cookie is set, then do this
		var mchimpguidenext = {
						id: "mailchimpnext-tour",
						steps: [
							{
							  title: "Select List Name",
							  content: "Select the list name you want to use with this subscribe form!",
							  target: "#mchimp_select_options",
							  placement: "top",
							  arrowOffset: 0,
							  yOffset: "5",
							  onNext: function() {}
							},
							{
							  title: "Subscribe Method",
							  content: "Check this if you want user subscribed directly without confirmation email! This may result in banned account if abused!",
							  target: "#disable_double_optin",
							  placement: "top",
							  arrowOffset: 0,
							  yOffset: "5"
							},
							{
							  title: "Save Form",
							  content: "Click this button to generate your subscribe form!",
							  target: "#publish",
							  placement: "left",
							  arrowOffset: 0,
							  onNext: function() {},
							  showNextButton: false,
							  nextOnTargetClick: true,
							  yOffset: "-15"
							}
						],
						  /* bubblePadding: "8", */
						  showPrevButton: true,
						  showNextButton: true,
						  fixedElement: false,
						  onStart: function() {}						
			};			
	}	
				var data = {
					action: "mchimp_admin",
					nonce: MchimpAdmin.SstssfbAdminAjax,
					id: identity,
					api: apikey,
					req_type: 'authentication',
					post_id : post_id
				};
				
				$.post(ajaxurl, data, function(response){
					$("#sstssfb_ajax_loader").hide();
				if(response != "invalid") {
					$(".mchimp_lists, #sstssfb_mchimp_result").remove();
					$(response).insertAfter(".submit_tr");
					$(".mchimp_row_hide").hide();
					if(typeof mchimpguidenext != "undefined") {
						hopscotch.startTour(mchimpguidenext, 0);
					}
				} else {
					$("#error_notification").text("Please make sure that your api credential is valid!");
					$("#sstssfb_mchimp_api_key").val("");
				}
				});
			});
	 } else {
		 $("#sstssfb_mchimp, #sstssfb_mchimp_result").hide();
		 $(".mchimp_lists").hide();
	 }
	 
	});	
});