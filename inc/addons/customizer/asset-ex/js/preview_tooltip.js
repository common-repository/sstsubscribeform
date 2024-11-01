jQuery(document).ready(function($){
	
	function validateEmail(email) {
		var re = /\S+@\S+\.\S+/;
		return re.test(email);
	}	
	
	$(".sstssfb_preview_vt").on("click touch", function(){	
		var previous = $("#previous_tooltip_position").val();
		var defaultval = previous == "" ? $("#sstssfb_theme_holder").find(".required_field").attr("class") : previous;
		$("#previous_tooltip_position").val(defaultval);		
		var position = $("#sstssfbvalidation-tooltip").val();
			position = position == "" ? "tooltip_left" : position;
		var position_m = position + "_m";
		var addclass = defaultval + " " + position;
		var required = $("#sstssfb_theme_holder").find(".required_field input");
		var email_address = $("#sstssfb_theme_holder").find("[data-field='email']").val();
		required.each(function(i, e){
			$(e).parent().removeClass(position_m);
			var value = $(e).val();
			if($.trim(value) == "") {
				allcorrect = false;
				$(e).parent().removeClass().addClass(addclass).removeClass("required_field_asterisk").attr("data-hint", "This field is required!");
				function ease() {
					$(e).parent().addClass(position_m);
				}
				setTimeout(ease, 200);
			}
			$(document).on("click touch", function(e){
				if(!$(e.target).is("#sstssfbvalidation-tooltip, .sstssfb_preview_vt, #sstssfb_theme_holder, #sstssfb_theme_holder *")){
					$("[data-hint]").removeClass().addClass(defaultval).removeAttr("data-hint");
				}
			});
		});
		
		if(email_address != "" && validateEmail(email_address) == false) {
			$("#sstssfb_theme_holder").find("[data-field='email']").parent().removeClass().removeClass("required_field_asterisk").addClass(addclass).attr("data-hint", "Email is not valid!");
				function ease() {
					$("#sstssfb_theme_holder").find("[data-field='email']").parent().addClass(position_m);
				}
				setTimeout(ease, 200);
		}		
		
	});
});