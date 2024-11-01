jQuery(document).ready(function($){	
	$(".post_title_label").tipsy({gravity: $.fn.tipsy.autoNS});
	
	/* PARENT CHECKED */
	if($("input[name='sstssfb_loc[parent]']:checked").length > 0) {
		if($("input[name='sstssfb_loc[parent]']:checked").val() == "all_loc") {
			$(".options_explanation").text("Exclude following pages:")
									 .css("opacity", 0)
									 .fadeIn(300)
									 .animate({color: "rgb(203, 88, 88)", opacity: 1}, {queue: false, duration: 800});
		} else {
			$(".options_explanation").text("Select pages:")
									 .css("opacity", 0)
									 .fadeIn(300)
									 .animate({color: "#318358", opacity: 1}, {queue: false, duration: 800})
									 .removeAttr("style");
		}
		$("#wrapper_option_main").show();
	}
	
	/*
	****** CHILDREN CHECKED ****** 
	*** mark post type 	
	*** mark taxonomy
	*/
	$(".detailed_item:checked").closest(".post_type_item")
							   .find(".post_type_header")
							   .css("color", "#e8d60b");

	$(".detailed_item:checked").closest(".wrappperdiv")
							   .find(".taxo_header")
							   .css("color", "#e8d60b");
	
	/*
	****** TAXONOMY CHECKED ******	
	*** mark post type
	*/
	$(".taxonomy_parent:checked").closest(".post_type_item")
						   .find(".post_type_header")
						   .css("color", "#e8d60b");
						   
	/* PARENT ON CHANGE */
	$("input[name='sstssfb_loc[parent]']").on("change", function(){
		if($(this).val() == "all_loc") {
			$(".options_explanation").text("Exclude following pages:")
									 .css("opacity", 0)
									 .fadeIn(300)
									 .animate({color: "rgb(203, 88, 88)", opacity: 1}, {queue: false, duration: 800});
		} else {
			$(".options_explanation").text("Select pages:")
									 .css("opacity", 0)
									 .fadeIn(300)
									 .animate({color: "#318358", opacity: 1}, {queue: false, duration: 800})
									 .removeAttr("style");
		}
		
		if($("#wrapper_option_main").css("display") == "none") {
			$("#wrapper_option_main").css("opacity", 0)
									.slideDown(300)
									.animate(
										{ opacity: 1 },
										{ queue: false, duration: 800 }					
									);
		}
	});
	
	/* PARENT OPEN CLOSE */
	$(".openclose_ptype").on("click touch", function(){
		var wrapper = $(this).closest(".post_type_item").find(".post_type_childrens");
			if(wrapper.css("display") == "none"){	
				wrapper
				.css("opacity", 0)
				.slideDown(300)
				.animate(
					{ opacity: 1 },
					{ queue: false, duration: 800 }					
				);
				$(this).removeClass("dashicons-arrow-down-alt2").addClass("dashicons-arrow-up-alt2");
			} else {
				wrapper
				.css("opacity", 0)				
				.slideUp(500);
				$(this).removeClass("dashicons-arrow-up-alt2").addClass("dashicons-arrow-down-alt2");		
			}
	});
	
	/* TAXO ON OPEN CLOSE */
	$(".openclose_taxo").on("click touch", function(){
		var wrapper = $(this).closest(".wrappperdiv").find(".taxonomy_childrens");
			if(wrapper.css("display") == "none"){	
				wrapper
				.css("opacity", 0)
				.slideDown(300)
				.animate(
					{ opacity: 1 },
					{ queue: false, duration: 800 }					
				);
				$(this).removeClass("dashicons-arrow-down-alt2").addClass("dashicons-arrow-up-alt2");
			} else {
				wrapper
				.css("opacity", 0)				
				.slideUp(500);
				$(this).removeClass("dashicons-arrow-up-alt2").addClass("dashicons-arrow-down-alt2");		
			}
	});
	
	/* POST TYPE ON CHANGE */
	$(".posttype_parent").on("change", function(){
		var childrens = $(this).closest(".post_type_item").find(".taxonomy_parent, .detailed_item");
		if($(this).prop("checked") == true){
			childrens.prop("checked", true);
		} else {
			childrens.prop("checked", false);
			$(this).closest(".post_type_item").find(".post_type_header").removeAttr("style");
		}
	});
	
	/* TAXONOMY ON CHANGE */
	$(".taxonomy_parent").on("change", function(){
		var childrens = $(this).closest(".wrappperdiv").find(".detailed_item");
		var fellows = $(this).closest(".post_type_childrens").find(".taxonomy_parent");
		var fellowslength = fellows.length;
		var fellowschecked = $(this).closest(".post_type_childrens").find(".taxonomy_parent:checked").length;
		var ptypeparent = $(this).closest(".post_type_item").find(".posttype_parent");		
		if($(this).prop("checked") == true){			
			childrens.prop("checked", true);
		} else {
			childrens.prop("checked", false);
			$(this).closest(".page_item_header").removeAttr("style");
			$(this).closest(".post_type_item").find(".post_type_header").removeAttr("style");
		}
		if(fellowslength == fellowschecked) {
			ptypeparent.prop("checked", true);
		} else {
			ptypeparent.prop("checked", false);
		}		
	});
	
	/* ITEM ON CHANGE */
	$(".detailed_item").on("change", function(){
		var fellowslength = $(this).closest(".wrappperdiv").find(".detailed_item").length;
		var fellowschecked = $(this).closest(".wrappperdiv").find(".detailed_item:checked").length;
		var taxoparent = $(this).closest(".wrappperdiv").find(".taxonomy_parent");		
		if(fellowslength == fellowschecked){
			taxoparent.prop("checked", true);
		} else {
			taxoparent.prop("checked", false);
		}		
		var allptypechildrens = $(this).closest(".post_type_item")
									   .find(".detailed_item, .taxonomy_parent")
									   .length;
		var ptypechildrenschecked = $(this).closest(".post_type_item")
										   .find(".detailed_item:checked, .taxonomy_parent:checked")
										   .length;	
		var ptypeparent = $(this)
						  .closest(".post_type_item")
						  .find(".posttype_parent");						  
		if(allptypechildrens == ptypechildrenschecked){
			ptypeparent.prop("checked", true);
		} else {
			ptypeparent.prop("checked", false);
		}
	});
	
	// Date
	$("#by_datea, #by_dateb").datepicker({
		dateFormat : 'yy-mm-dd'
	});
	
	$("#by_datea, #by_dateb").on('change', function(){
		var field_a = $("#by_datea").val();
		var field_b = $("#by_dateb").val();			
		if(Date.parse(field_a) > Date.parse(field_b)) {
			$("#by_dateb").val(field_a);
		}
	});		
	
	$("#clear_dates").on('click', function(){
		var field_a = $("#by_datea").val();
		var field_b = $("#by_dateb").val();		
		if(field_a != "") {
			$("#by_datea").val("");
		}		
		if(field_b != "") {
			$("#by_dateb").val("");
		}
	});
	
	$("#by_datea, #by_dateb").datepicker({
	dateFormat : 'yy-mm-dd'
	});
});	