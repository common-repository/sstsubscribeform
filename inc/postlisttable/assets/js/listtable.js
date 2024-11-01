jQuery(document).ready(function($){
	$(".incomplete_email").tipsy({gravity: 'e', live: true});
	$(".sstssfb_acdc").tipsy({gravity: 'se', live: true});
	$(".row-title, .action_icons").tipsy({gravity: $.fn.tipsy.autoNS, live: true});
				var itemtext = $(".displaying-num").html();
				var totalpages = $(".total-pages").html();
				itemnum = itemtext.match(/\d+/);
				if(itemnum != 0 && totalpages != 1 && totalpages != 0) {
					$(".sstssfb_pagination_head").fadeIn(500).css("display", "inline-block");
				}
				
	// Activate Deactivate subscribe form
	$(".sstssfb_action_icons .action_icons").on("click touch", function(){		
		
		var idnumber = null;
		var clicked = null;
		clicked = $(this);		
		if(clicked.hasClass("incomplete_email") || clicked.hasClass("noactive")) {
			return;
		}
		var parentwrapper = clicked.closest(".sstssfb_action_icons");
		idnumber = parentwrapper.find(".idnumber").val();		
		$(".sstssfb_actions_blocker").show();
		
		if(clicked.hasClass("sstssfb_acdc")) {
				
			clicked.css("color", "#ffbf00");
			$(".sstssfb_actions_loader").fadeIn(1000);		
			var data = {
					action: "active_deactive",
					nonce : ActionIcons.SstssfbUserActions,
					id	  : idnumber 
				};
			$.post(ajaxurl, data, function(response) {
				
				if(response == "null") {
					clicked.css("color", "");
					$(".sstssfb_actions_loader").hide();
					$(".sstssfb_actions_blocker").hide();
					return;
				}
				
				if(clicked.hasClass("active")) {
					clicked.removeClass("active dashicons-unlock").addClass("inactive dashicons-lock").attr("title", "Click to activate!");
				} else if(clicked.hasClass("inactive")) {
					clicked.removeClass("inactive dashicons-lock").addClass("active dashicons-unlock").attr("title", "Click to deactivate!");
				}
				clicked.css("color", "");
				$(".tipsy-inner").text(clicked.attr("title"));
				$(".sstssfb_actions_loader").hide();
				$(".sstssfb_actions_blocker").hide();
			});
			
		} else if(clicked.hasClass("sstssfb_delete")) {	
			parentwrapper.find(".sstssfb_confirm_deletion").show();			
		} else if(clicked.hasClass("sstssfb_clone")) {
		$(".sstssfb_actions_loader").fadeIn(1000);
			var data = {
				action: "clone_form",
				nonce : ActionIcons.SstssfbUserActions,
				id	  : idnumber 
			};
			
			$.post(ajaxurl, data, function(response) {
				if(response == 0) {
					
				}
				location.href = location.href;
				$(".sstssfb_actions_loader").hide();
			});
			return;
		}
		return;
	});
	
			$(".del_confirm, .del_cancel").on("click touch", function(){
				var idnumber = $(this).closest(".sstssfb_action_icons").find(".idnumber").val();
				$(".sstssfb_actions_blocker").show();
				var row = $(this).closest("tr");
				
				if($(this).attr("class") == "del_cancel") {
					$(".sstssfb_confirm_deletion, .sstssfb_actions_blocker").hide();
					return;
				}
				$(".sstssfb_confirm_deletion").hide();
				var itemtext = $(".displaying-num").html();
				var	itemnum = itemtext.match(/\d+/);
				var text = itemtext.replace(itemnum, "");
				var itemnumnow = itemnum - 1;
				text = itemnumnow + text;
				
				$(".sstssfb_actions_loader").fadeIn(1000);
				
				var data = {
					action: "delete_form",
					nonce : ActionIcons.SstssfbUserActions,
					id	  : idnumber 
				};	
				
				$.post(ajaxurl, data, function(response) {
					if(response == idnumber) {
						row.fadeOut(2000, function(){
							$(this).remove();						
							setTimeout(function(){							
								if($(".wp-list-table #the-list").find("tr").length < 1) {	
									var url = location.href;
									if(url.indexOf("&paged=") >= 0) {
										var pagenum = url.slice(-1);
										var page = "paged=" + pagenum;
										var pagebefore = "paged=" + (pagenum - 1);
										
										if(pagenum > 2) {
											url = url.replace(page, pagebefore);
										} else {
											url = url.replace("&" + page, "");
										}
									}
									location.href = url;
									return;
								} 
								$(".sstssfb_actions_loader, .sstssfb_actions_blocker").hide();
							}, 200);
						});
						
						$(".displaying-num").fadeOut(2500, function() {
							$(this).html(text).fadeIn(1000);						
						});						
					 				
					} else if(response != idnumber && response != "") {						
						$(".sstssfb_actions_loader, .sstssfb_actions_blocker").hide();
						alert("There is something wrong when deleting the form! Please reload the page or contact your webhosting support if the problem occurs!");
						alert(response);
						$(".sstssfb_actions_loader, .sstssfb_actions_blocker").hide();
					}
				});
			});
});