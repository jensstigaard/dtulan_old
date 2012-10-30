$(document).ready(function(){
	$('#sponsors a, #header a').hover(function(){
		$(this).find('img').stop().fadeTo(500, 1);
	}, function(){
		$(this).find('img').stop().fadeTo(800, 0.5);
	});


	$( ".tabs" ).tabs({
		beforeLoad: function( event, ui ) {
			ui.jqXHR.error(function() {
				ui.panel.html(
					"Couldn't load this tab. We'll try to fix this as soon as possible. "
					);
			});
		},
		select: function(event, ui) {
			$('#loading_indicator').show();
		},
		load:   function(event, ui) {
			$('#loading_indicator').hide();
		}
	});
});