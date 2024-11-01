jQuery(document).ready(function($){
	function validateEmail(email) {
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}

	$("[data-role='submit']").on("click touch", function(e){
		e.preventDefault();
		var allcorrect = true;
		var button = $(this);
		var this_id = button.closest(".sstssfb_main_wrapper").attr("id");
		var this_class = button.closest(".sstssfb_main_wrapper");
		var this_wrapper = this_class.find(".first_sstform_wrapper");
		var redirect = this_class.attr("data-redirect");
		button.attr("disabled", true);
		this_class.find(".sstssfb_loader").fadeIn().slideDown(300, function(){
			$(this).children().show().addClass("roll");
		});
		var tooltip = this_class.attr("data-tooltip");		
			tooltip = tooltip != null ? tooltip : "tooltip_left";
		var tooltip_m = tooltip + "_m";
		
		$("#sstssfb_temporary_button").remove();
		$('<style id="sstssfb_temporary_button" type="text/css"> #' + this_id + ' [data-role="submit"] { box-shadow: none !important; } </style>').appendTo("head");
		
		var mainwrapper = $(this).closest(".sstssfb_main_wrapper");	
		var email_address = mainwrapper.find("[data-field='email']").val();
		var required = mainwrapper.find(".required_field input");

		function closeloader() {
			this_class.find(".sstssfb_loader").slideUp(500, function(){
				$(this).fadeOut().children().removeClass("roll");
			});
			button.attr("disabled", false);
			$("#sstssfb_temporary_button").remove();	
		}
		
		required.each(function(i, e){
			var value = $(e).val();
			if($.trim(value) == "") {
				allcorrect = false;
				$(e).parent().removeClass("required_field_asterisk " + tooltip_m).addClass(tooltip).attr("data-hint", "This field is required!");
				function ease() {
					$(e).parent().addClass(tooltip_m);
				}
				setTimeout(ease, 200);				
			}
			$(e).on("keyup change paste", function(){
				if($(e).parent().hasClass(tooltip)) {
					$(e).parent().removeClass(tooltip + " " + tooltip_m).removeAttr("data-hint").addClass("required_field_asterisk");
				}
			});
			
			$(document).on("click", function(e){
				if(!$(e.target).is(".sstssfb_main_wrapper *")){					
					$("[data-hint]").attr('class', function(i, v){
							return v.replace(/(^|\s)tooltip_\S+/g, '');
					})
					.removeAttr("data-hint")
					.addClass("required_field_asterisk");
				}
			});
		});
		
		/* invalid email */
		if(email_address != "" && validateEmail(email_address) == false) {
			allcorrect = false;
			mainwrapper.find("[data-field='email']").parent().removeClass("required_field_asterisk " + tooltip_m).addClass(tooltip).attr("data-hint", "Email is not valid!");
				function ease() {
					mainwrapper.find("[data-field='email']").parent().addClass(tooltip_m);
				}
				setTimeout(ease, 200);
		}
		
		if(allcorrect == false) {
			closeloader();
			return;
		}
		
		var formdata = {};				
		mainwrapper.find("[data-field]")
				.each(function(i, val){
					var field = $(this).attr("data-field");
					formdata[field] = $(this).val() || "";
			});
		formdata['id'] = button.closest("[data-id]").attr("data-id");
		formdata['service'] = button.closest("[data-service]").attr("data-service");
		
		var data = {
			action: "mailsubscribe_front",
			security: eMailFrontend.SstssfbMailFrontAjax,
			values: formdata
		}
		// ajax
		$.post(eMailFrontend.ajaxurl, data, function(response){
			button.attr("disabled", false);
			$("#sstssfb_temporary_button").remove();			
			var heading = "";
			var detail = "";
			var errorresponse = false;
			var closeform = false;
			if(response == "deny") {
				heading = "Already Subscribed! ";
				detail = "This email is already subscribed!";
				closeform = true;
			} else if(response == "confirmation") {
				heading = "Check Email! ";
				detail = "This email has been registered! Please check the message sent to your email to confirm your subscription!";
				closeform = true;
			} else if(response == "subscriptionsubscribed") {
				heading = "Success!";
				detail = "You have been successfully subscribed!";
				closeform = true;
			} else if(response == "updatesubscribed") {
				heading = "Subscription updated! ";
				detail = "Your subscription has been updated!";
				closeform = true;
			} else if(response == "subscriptionpending" || response == "updatepending") {
				heading = "Confirmation Required! ";
				detail = "Please check your email to confirm your subscription!";
				closeform = true;
			} else if(response == ""){
				heading = "Unknown Error! ";
				detail = "Please check your internet connection or contact this site's owner, or close this message to retry!";
				errorresponse = true;
			} else if(response.indexOf("Warning") >= 0 || response.indexOf("Fatal") >= 0){
				heading = "Site's Error! ";
				detail = "Please contact this site's owner or close this message to retry!";
				errorresponse = true;
			} else {
				heading = "Error! ";
				detail = response;
				errorresponse = true;
			}
			var messageblock = '<div class="response">';
				messageblock += '<div class="responseinner">';
				messageblock += '<span class="sst_heading_text">' + heading + '</span>';
				messageblock += '<span>' + detail + '</span>';				
				messageblock += '</div>';
				messageblock += '<div class="closeresponse">X</div>';
				messageblock += '</div>';
			this_class.find(".sstssfb_loader").slideUp(500, function(){
				$(this).fadeOut().children().removeClass("roll");
				$(messageblock).appendTo(this_wrapper).fadeIn(500);
				if(errorresponse == true) {
					$(".response, .responseinner").css("background", "#990000");
				} else {
					if(redirect != null) {
						function redir() {
							window.location = redirect;
						}
						setTimeout(redir, 500);
					}
				}
				
				$(".closeresponse").on("click touch", function(){
						$(".response").fadeOut(500, function(){
							$(this).remove();
						});
				});
				
			});	
		});
	});
});