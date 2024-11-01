jQuery(document).ready(function($){
    // Define the tour!
    var quickguide = {
      id: "quickguide-tour",
      steps: [
        {
          title: "Give identity",
          content: "Give unique identifier for your form. You can use any words here! This step is optional, just to make identifying your form easier!",
          target: "#title-prompt-text",
          placement: "bottom",
		  arrowOffset: 'center',
		  yOffset: "-5",
		  onNext: function() {}
        },		
        {
          title: "Select theme",
          content: "Select any theme by clicking any thumbnail listed here! The selected theme will be marked by yellow border.",
          target: "#sstssfb_themeslist",
          placement: "top",
		  arrowOffset: 'center',
		  yOffset: "5",
		  xOffset: "center",
		  onNext: function() {
			  $("#ui-id-2").trigger("click");
		  }
        },		
        {
          title: "Select Email Service",
          content: "Select the email service provider for your subscribe form using this dropdown select, then wait for the service registration form to be loaded in this page! This step is required!",
          target: "#sstssfb_selectservice",
          placement: "top",
		  arrowOffset: 0,
		  yOffset: "5",
		  onNext: function() {},
		  onPrev: function() {
			   $("#ui-id-1").trigger("click");
		  },
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
	
    // Start the tour!
	$("#sstssfbstarttour").on("click touch", function(){
		$("#ui-id-1").trigger("click");
		$("#sstssfbguideopening").fadeIn();
		$("#sstssfbstarttour_block").show();
	});
	
	// Quick Start Guide
	$("#sstssfbquickguide").on("click touch", function(){
		$("#sstssfbstarttour_block, #sstssfbguideopening").hide();
		
		$("#quickguidetourselected").val("yes");
		
		hopscotch.startTour(quickguide, 0);
	});
	
	
	$(".closeintroduction").on("click touch", function(){
		$("#sstssfbstarttour_block, #sstssfbguideopening").hide();
		$("#advancedguidetourselected").val("no");
		$("#quickguidetourselected").val("no");
	});	
});