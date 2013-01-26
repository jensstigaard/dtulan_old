$(document).ready(function(){
	$('#header a, #sponsors a').hover(function(){
		$(this).find('img').stop().fadeTo(500, 1);
	}, function(){
		$(this).find('img').stop().fadeTo(800, 0.5);
	});


	//	$( ".tabs" ).tabs({
	//		beforeLoad: function( event, ui ) {
	//			ui.jqXHR.error(function() {
	//				ui.panel.html(
	//					"Couldn't load this tab. We'll try to fix this as soon as possible!"
	//					);
	//			});
	//		},
	//		select: function(event, ui) {
	//			$('#loading_indicator').show();
	//		},
	//		load:   function(event, ui) {
	//			$('#loading_indicator').hide();
	//		}
	//	})
	//	;

	$( ".tabs" ).tabs({
		//		beforeLoad: function( event, ui ) {
		//			ui.jqXHR.error(function() {
		//				ui.panel.html(
		//					);
		//			});
		//		},
		beforeLoad: function(event, ui) {
			$(this).find('.loading_indicator').show();
			
			ui.jqXHR.error(function() {
				ui.panel.html(
					"<p>Couldn't load this tab. We'll try to fix this as soon as possible!</p>");
					
				$(this).find('.loading_indicator').hide();
			});
		},
		load:   function(event, ui) {
			$(this).find('.loading_indicator').hide();
		}
	})
	;
	
	
	$(".fancybox").fancybox();
	
	$('#lan_overview .lan_overview_item').tooltip();


	$(".fittext").fitText();

});