jQuery(document).ready(function($){
	if($(".sstssfb_mail_result").length > 0) {
		$(".cancel_new_account").show();
	}
	
	$(".cancel_new_account").on("click touch", function(){
		$(".sstssfb_mail_result").show();
		$(".mail_service_option").hide();
	});
	
	$(".add_new_account").on("click touch", function(){
		$(this).closest(".sstssfb_mail_result").hide();
		$(".mail_service_option").show();
		$(".mail_row_hide").show();
	});
});