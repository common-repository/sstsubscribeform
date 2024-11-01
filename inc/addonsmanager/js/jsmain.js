jQuery(document).ready(function($){
	$("#sstssfbupload_addon").on("change", function(){
		$(".file_info").html($(this).val()).addClass("sppanstyle");
	});
	
	$("#sstssfbstartinstall").on("click touch", function(e){
		if($("#sstssfbupload_addon").val() == "") {
			e.preventDefault();
			var type = "addon";
			if($(this).hasClass("themeupload")) {
				type = "theme";
			}
			
			$(".file_info").html("Please select " + type + " file from your computer!").addClass("sppanstyle");
		}
	});	
});