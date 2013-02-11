$(document).ready(function(){
	$('#header a, #sponsors a').hover(function(){
		$(this).find('img').stop().fadeTo(500, 1);
	}, function(){
		$(this).find('img').stop().fadeTo(800, 0.5);
	});
	
	//	$('.nav-tabs a').click(function (e) {
	//		
	//		// get the div's id
	//		var tab_content_element_id = $(this).attr('href').substr(1);
	//		
	//		var url = $(this).attr('data-link');
	//		
	//		showTab(url, tab_content_element_id);
	//	});
	//	
	//	$('.nav-tabs a:first-child').each(function(){
	//		// get the div's id
	//		var tab_content_element_id = $(this).attr('href').substr(1);
	//		
	//		var url = $(this).attr('data-link');
	//		
	//		showTab(url, tab_content_element_id);
	//	});
	//	
	//	function showTab(url, target){
	//		$.ajax({
	//			url: url,
	//			success: function(data){
	//				$("#"+target).html(data);
	//			}
	//		}
	//		);
	//	}

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
	
	$('#lan_overview .lan_overview_item, #tournament-winners .item .person').tooltip();


	$(".fittext").fitText();

});