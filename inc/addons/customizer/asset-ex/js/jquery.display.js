jQuery(document).ready(function($){
	function addWidth(children, parent) {
		var twidth = $(children).children().outerWidth();
		if($("#sstssfb_width_auto").prop("checked") == false) {
			$("#sstssfbmainwidth-val").spinner("value", twidth);
			$(parent).css({"width": twidth, "background": "transparent"});
		} else {
			$("#sstssfbmainwidth-val").spinner("value", "250");
			$(parent).css({"width": "500px", "background": "transparent"});
		}
	}
	
	function addHeight(children, parent) {
		var theight = $(children).outerHeight();
		$(parent).css({"height": theight});				
	}
	
	$("#sstssfb_true_false").val("false");
	// load the theme
	$("#sstssfb_themedesigner").on("click touch", function(e){
		var stylesheet_var = null;
		$("#sstssfb_close_customizer").hide();
		$("#sstssfb_customizerwrapper").show();
		$(".theme_cssload-line").show();
		var previous_theme = $("#sstssfb_previous_theme").val();
		var theme = $("input[name='sstssfb[theme]']:checked").val();
		$("#sstssfb_previous_theme").val(theme);
		var url_css = $("#sstssfb_saved_css_url").val();
		url_css = url_css + theme + ".css";
		
		$("#sstssfb_csrthemewrapper").removeAttr("style");
		$("#sstssfb_theme_holder").hide();
		stylesheet_var = "<link rel='stylesheet' href='" + url_css + "' type='text/css' id='sstssfb_theme_css'/>";
			setTimeout(function() {			
				$("#sstssfb_theme_css").remove();
				$(stylesheet_var).appendTo("head");
			}, 100);
		var edit = $("#sstssfb_custom_themestyle").val();
		var saved = $("#sstssfb_saved_themehtml").val();
		var saved_themeurl = $("#sstssfb_saved_themeurl").val();
		var saved_id = $("#sstssfb_saved_themehtml_identity").val();
			saved_id = $.trim(saved_id);
		if(typeof saved != "undefined") {
			var correct_theme = saved.indexOf(saved_id) >= 0;
		} else {
			var correct_theme = true;
		}
		
		$("#sstssfb_theme_holder").html("");
		if($("#sstssfb_load_original").prop("checked") == false) {
		if($.trim(saved) != "" && saved_themeurl == theme && ($.trim(saved) == $.trim(edit)) && correct_theme) {
			//load saved
			$(saved).appendTo("#sstssfb_theme_holder");
			$("#sstssfb_close_customizer").show();
			$(".theme_cssload-line").hide();
			setTimeout(function() {
				addWidth("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");				
			}, 300);
			setTimeout(function() {
				addHeight("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 200);
			var whd = $("#sstssfb_theme_holder").html();
			var whd = whd.replace(/\s/g, "");
			if(whd.indexOf("width:auto;") >= 0) {
			$("#sstssfb_width_auto").prop("checked", true);
			$("#sstssfbmainwidth-val").spinner("disable");
			$("#sstssfb_csrthemewrapper").css("width", "500px");
			} else {
				$("#sstssfb_width_auto").prop("checked", false);
				$("#sstssfbmainwidth-val").spinner("enable");
			}
			setTimeout(function() {			
				$("#sstssfb_theme_css").remove();
				$(stylesheet_var).appendTo("head");
				$("#sstssfb_theme_holder").fadeIn();
			}, 100);
			return;
		}
		
		var curr_id = $("#sstssfb_edited_themehtmlidentity").val();
		if($.trim(curr_id) != "") {
			correct_theme_id = edit.indexOf(curr_id) >= 0;
		} else {
			correct_theme_id = false;
		}
		if(previous_theme == theme && correct_theme_id) {
			$(edit).appendTo("#sstssfb_theme_holder");
			$("#sstssfb_close_customizer").show();
			$(".theme_cssload-line").hide();
			setTimeout(function() {
				addWidth("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 100);		
			setTimeout(function() {
				addHeight("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 100);
			var whd = $("#sstssfb_theme_holder").html();
			var whd = whd.replace(/\s/g, "");
			if(whd.indexOf("width:auto;") >= 0) {
			$("#sstssfb_width_auto").prop("checked", true);
			$("#sstssfbmainwidth-val").spinner("disable");
			$("#sstssfb_csrthemewrapper").css("width", "500px");
			} else {
				$("#sstssfb_width_auto").prop("checked", false);
				$("#sstssfbmainwidth-val").spinner("enable");
			}
			setTimeout(function() {			
				$("#sstssfb_theme_css").remove();
				$(stylesheet_var).appendTo("head");
				$("#sstssfb_theme_holder").fadeIn();
			}, 100);
			return;
		}
		}
		$("#sstssfb_custom_themestyle").val("");
		
		// AJAX
			var data = {
				action: "cstmzr_ajax",
				nonce: CstmzrAdmin.SstssfbCstmzrAjax,
				themeurl: theme
			};
		$.post(ajaxurl, data, function(response){
			setTimeout(function() {			
				$("#sstssfb_theme_css").remove();
				$(stylesheet_var).appendTo("head");
				$("#sstssfb_theme_holder").fadeIn();
			}, 100);	
			$(response).appendTo("#sstssfb_theme_holder");
			$("#sstssfb_theme_selectors").appendTo("#sstssfb_data_container");
			$("#sstssfb_true_false").val("true");
			$(".theme_cssload-line").hide();			
			$("#sstssfb_close_customizer").show();
			setTimeout(function() {
				addWidth("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 100);
			setTimeout(function() {
				addHeight("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 100);
			var whd = $("#sstssfb_theme_holder").html();
			var whd = whd.replace(/\s/g, "");
			if(whd.indexOf("width:auto;") >= 0) {
			$("#sstssfb_width_auto").prop("checked", true);
			$("#sstssfbmainwidth-val").spinner("disable");
			$("#sstssfb_csrthemewrapper").css("width", "500px");
			} else {
				$("#sstssfb_width_auto").prop("checked", false);
				$("#sstssfbmainwidth-val").spinner("enable");
			}
			$("#sstssfb_theme_holder button").on("click touch", function(e){
				e.preventDefault();
			});
		});
		
			$("input[name='sstssfb[theme]']").on("change", function(){
				var theme = $("input[name='sstssfb[theme]']:checked").val();				
				var saved_themeurl = $("#sstssfb_saved_themeurl").val();
				var saved = $("#sstssfb_saved_themehtml").val();
				var temp = $("#sstssfb_custom_themestyle").val();
				var edit_other = $("#sstssfb_true_false").val();
				var saved_id = $("#sstssfb_saved_themehtml_identity").val();
					saved_id = $.trim(saved_id);				
					var tempcompare = "same";
					var savedcompare = "same";	
				if(typeof saved != "undefined") {
					var tempcompare = temp.replace(/\s/g, "");				
					var savedcompare = saved.replace(/\s/g, "");
					var correct_theme = saved.indexOf(saved_id) >= 0;
				} else {
					var correct_theme = true;
				}
				if(saved_themeurl == theme && $.trim(saved) != "" && $.trim(tempcompare) != $.trim(savedcompare) && correct_theme) {
					$("#sstssfb_true_false").val("false");
					$("#sstssfb_load_savedtheme").removeClass("hidden");				
				} else {
					$("#sstssfb_true_false").val("true");
					$("#sstssfb_load_savedtheme").addClass("hidden");					
				}
				return;				
			});		
	});
	
// Theme change
$("input[name='sstssfb[theme]']").on("change", function(){
	var theme = $("input[name='sstssfb[theme]']").filter(":checked").val();
	var previous_theme = $("#sstssfb_previous_theme").val();
	var saved_themeurl = $("#sstssfb_saved_themeurl").val();
	var edit = $("#sstssfb_custom_themestyle").val();		
	var saved = $("#sstssfb_saved_themehtml").val();
	var saved_id = $("#sstssfb_saved_themehtml_identity").val();
		saved_id = $.trim(saved_id);		
		if(typeof saved != "undefined") {
			var correct_theme = saved.indexOf(saved_id) >= 0;
		} else {
			var correct_theme = true;
		}
	var onholder = $("#sstssfb_theme_holder").find("*").removeClass("selected curr_edited").end().html();
		onholder = onholder.replace(/\s/g, "");
		editcom = edit.replace(/\s/g, "");

		var curr_id = $("#sstssfb_edited_themehtmlidentity").val();
		if(curr_id != "") {
			correct_theme_id = edit.indexOf(curr_id) >= 0;
		} else {
			correct_theme_id = correct_theme;
		}	
		
		/* need revision may be */
	if((previous_theme == theme && $.trim(edit) != "" && $.trim(editcom) != $.trim(onholder) && correct_theme_id) || (saved_themeurl == theme && $.trim(saved) != "") && correct_theme) {
		$("#sstssfb_use_edited_label").removeClass("hidden").text("front end = Edited");
		$("#sstssfb_use_edited_theme").prop("checked", true);
	} else {
		$("#sstssfb_use_edited_label").addClass("hidden").text("front end = Default");
		$("#sstssfb_use_edited_theme").prop("checked", false);
	}
		/* need revision may be */
		if(saved_themeurl == theme && $.trim(saved) != "" && $.trim(saved) != $.trim(edit) && correct_theme) {
			$("#sstssfb_load_savedtheme").removeClass("hidden");		
		} else {
			$("#sstssfb_load_savedtheme").addClass("hidden");		
		}
	$("#sstssfb_themeurlto_save").val(theme);
	$("#sstssfb_true_false").val("true");
});

	// Use default/ edited
	$("#sstssfb_use_edited_theme").on("change", function(){
		if($(this).prop("checked") == true) {
			$("#sstssfb_use_edited_label").text("front end = Edited");
		} else {
			$("#sstssfb_use_edited_label").text("front end = Default");
		}
	});

	if($("#sstssfb_use_edited_theme").prop("checked") == true) {
		$("#sstssfb_use_edited_label").removeClass("hidden").text("front end = Edited");
	}

	// CLOSE CUSTOMIZER PANEL
	$("#sstssfb_close_customizer").on("click touch", function(e){
		$("#sstssfb_customizerwrapper").hide();
		var edited_theme = $("#sstssfb_theme_holder").find("*").removeClass("selected curr_edited").end().html();
		var edited_theme = edited_theme.replace(/none repeat scroll 0% 0%/g, "");
		$("#sstssfb_custom_themestyle").val(edited_theme);	
		var theme = $("input[name='sstssfb[theme]']:checked").val();
		var edit = $("#sstssfb_custom_themestyle").val();
		var saved_themeurl = $("#sstssfb_saved_themeurl").val();
		var saved = $("#sstssfb_saved_themehtml").val();	
		var saved_id = $("#sstssfb_saved_themehtml_identity").val();
		if(typeof saved != "undefined") {
			var correct_theme = saved.indexOf(saved_id) >= 0;
		} else {
			var correct_theme = true;
		}
		var curr_id = $("#sstssfb_theme_holder").find(".first_sstform_wrapper").attr("id");
		$("#sstssfb_edited_themehtmlidentity").val(curr_id);
		if(saved_themeurl == theme && $.trim(saved) != "" && $.trim(saved) != $.trim(edit) && correct_theme) {
			$("#sstssfb_load_savedtheme").removeClass("hidden");		
		} else {
			$("#sstssfb_load_savedtheme").addClass("hidden");		
		}
		if($.trim(edit).indexOf("style=") >= 0) {
			$("#sstssfb_use_edited_label").removeClass("hidden").text("front end = Edited");
			$("#sstssfb_use_edited_theme").prop("checked", true);
		} else {
			$("#sstssfb_use_edited_label").addClass("hidden").text("front end = Default");
			$("#sstssfb_use_edited_theme").prop("checked", false);			
		}
		$("#sstssfb_themeurlto_save").val(theme);
	});

// Use Saved Edit 
$("#sstssfb_load_savedtheme").on("click touch", function(){
	var saved = $("#sstssfb_saved_themehtml").val();
	var edit = $("#sstssfb_custom_themestyle");
		edit.val(saved);
	$(this).addClass("hidden");
});	
	// hide tool panel
	$(".hidetool").on("click touch", function(){
		var handle = $(this);
		handle.hide();
		var w = $("#designcontroller").outerWidth();
		$("#designcontroller").animate({"left": "-" + w}, 300, function(){
			$(".showtool").show().animate({"left": "-10px"}, 300, function(){
				$(".showtool").css("left", 0);
			});
		});
	});
	
	// show tool panel
	$(".showtool").on("click touch", function(){
		var handle = $(this);
		handle.hide();
		var w = $("#designcontroller").outerWidth();
		$("#designcontroller").animate({"left": "5px"}, 300, function(){
			$(".hidetool").show();
		});		
	});	
	$("#designcontroller").niceScroll({horizrailenabled:false}); // nicescroll

// Remove style
$("#sstssfb_clean_style").on("click touch", function(){
	$("#sstssfb_theme_holder").find(".curr_edited").removeClass("selected").removeAttr("style");
	$(this).addClass("hidden");
});
// Remove All styles
$("#sstssfb_clean_all_styles").on("click touch", function(){
	$("#sstssfb_theme_holder").find("*").removeAttr("style");
	$("#sstssfb_width_auto").prop("checked", false);
	$("#sstssfbmainwidth-val").spinner("enable");
			setTimeout(function() {
				addWidth("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 100);		
			setTimeout(function() {
				addHeight("#sstssfb_theme_holder", "#sstssfb_csrthemewrapper");
			}, 100);
});
// Remove marker
$("#sstssfb_clean_marker").on("click touch", function(){
	$("#sstssfb_theme_holder").find(".curr_edited").removeClass("selected curr_edited");
	$("#sstssfb_clean_marker, #sstssfb_clean_style").addClass("hidden");
});
	
// Edit
	
	// Select element
	$("#sstssfb_theme_holder").on("click touch touchstart mouseover", function(e){
		var element = $(e.target);
		if(e.type == "mouseover" || e.type == "touchstart"){ // mouseover
			element.addClass("hovered").parents().removeClass("hovered");
			element.on("mouseleave touchend", function() {
				$(this).removeClass("hovered");
			});
		}
		if(e.type == "click" || e.type == "touch") { // click
			e.preventDefault();			
			$("#sstssfbcontent-settings").text("");
				if(e.altKey) { // Alt key pressed
					element.addClass("curr_edited");
					$(".curr_edited").addClass("selected");						
				} else {
					element.closest("#sstssfb_theme_holder").find("*").removeClass("selected curr_edited");
					element.addClass("selected curr_edited");
						// write text to the textarea
							var thetext = element.contents().filter(function(){
												return this.nodeType === 3;
											}).text().trim();
					$("#sstssfbcontent-settings").val(thetext.trim());
				}							
						var bgrnd = element.css("backgroundColor");
						var txtclr = element.css("color");
						var fontfmily = element.css("font-family");		
						var textalign = element.css("text-align");		
			
							$("#sstssfbfontfmly-settings").val(fontfmily);
							$("#sstssfbtextalign-settings").val(textalign);
							$("#sstssfbmaincolor-settings").spectrum({
								preferredFormat: "rgb",
								showAlpha: true,
								move: function(color) {
									$(this).closest(".sstssfb-clr-wrapper").find(".sstssfbsettings-input-val").val(color);
									$(".curr_edited").removeClass("selected").css("background", color);
								},
								change: function(color) {
									$(this).val(color);
								}
							});
							$("#sstssfbtextcolor-settings").spectrum({
								preferredFormat: "rgb",
								showAlpha: true,
								move: function(color) {
									var textcolor = $(this).closest(".sstssfb-clr-wrapper").find(".sstssfbsettings-input-val");
									textcolor.val(color);
									$(".curr_edited").removeClass("selected").css({"color": textcolor.val()});
								},
								change: function(color) {
									$(this).val(color);
								}
							});
						var targeted = $("#sstssfb_theme_holder").find(".selected, .curr_edited").length;
						if(targeted > 0) {
							$("#sstssfb_clean_marker, #sstssfb_clean_style").removeClass("hidden");
						}
		}
	});

	$(".sstssfbsettings-input-val").on("keyup paste change", function(){
		$(this).closest(".sstssfb-clr-wrapper").find(".sp-preview-inner").css("background", $(this).val());
	});
	
	// SPINNER - Width
	$("#sstssfbmainwidth-val").spinner({
		spin: function( event, ui ) {
			$("#sstssfb_theme_holder").children().css("width", ui.value).end().parent().css("width", ui.value);
		}
	});
	$("#sstssfbmainwidth-val").on("keyup", function() {
		$("#sstssfb_theme_holder").children().css("width", $(this).val()).end().parent().css("width", $(this).val());
	});
	//AUTO - Width
	$("#sstssfb_width_auto").on("change", function(e) {
		if($(this).prop("checked") == true) {
			$("#sstssfbmainwidth-val").spinner("disable");
			$("#sstssfb_theme_holder").children().css("width", "auto").end().parent().css("width", "500px");
		} else {
			$("#sstssfbmainwidth-val").spinner("enable");
			var spinner_val = $("#sstssfbmainwidth-val").spinner("value");
			$("#sstssfb_theme_holder").children().css("width", spinner_val).end().parent().css("width", spinner_val);
		}
	});
	
	// background spectrum color picker
	$("#sstssfbmaincolor-settings").spectrum({});	
	
	// text spectrum color picker
	$("#sstssfbtextcolor-settings").spectrum({});
	
	// Font Family
	$("#sstssfbfontfmly-settings").on("change", function(){
		$(".curr_edited").removeClass("selected").css({"font-family": $(this).val()});
	});
	
	// Text Align
	$("#sstssfbtextalign-settings").on("change", function(){
		$(".curr_edited").removeClass("selected").css({"text-align": $(this).val()});
	});
	
	// Text Change
	$("#sstssfbcontent-settings").on("change keyup paste", function(){
		// get current element
		
		if($(".curr_edited").length >= 2){
			return;
		}
		
		var thehtml = $(".curr_edited").html();
		var thetext = $(".curr_edited").contents().filter(function(){
							return this.nodeType === 3;
						}).text().trim();		
		var thechange = $(this).val();
		
		// if has element, then keep it, add text and put back			
		var thepurehtml = thehtml.replace(thetext, "");
		var theoutput = thechange.replace(/\<|\>/g, "");
		if($.trim(thepurehtml) != '') {
			var theoutput = thechange + thepurehtml;
		}
		$(".curr_edited").html("").removeClass("selected").html(theoutput);
		return;
	});
	
/* 	tinymce.init({
    selector: "#sstssfbcontent-settings",
    inline: true,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});*/
});