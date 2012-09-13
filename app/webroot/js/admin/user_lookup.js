$(document).ready(function(){

	$user_lookup = $('#user_lookup');
	$input = $user_lookup.find('input');
	var urlLookup = $user_lookup.find('#urlLookup a').attr('href');
	var urlRedirect = $user_lookup.find('#urlRedirect a').attr('href');

	$('#user_lookup input').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: urlLookup,
				//				url: "http://ws.geonames.org/searchJSON",
				dataType: "json",
				type : 'POST',
				data: {
					featureClass: "P",
					style: "full",
					maxRows: 12,
					search_startsWith: request.term
				},
				success: function( data ) {
					response( $.map( data.users, function( item ) {
						return {
							label: item.User.name,
							id_number: item.User.id_number,
							email: item.User.email,
							id: item.User.id,
							value: item.User.id_number
						}
					}));
				}
			});
		},
		minLength : 3,
		selectFirst : true,
		select: function( event, ui ) {
			if(ui.item){
				document.location = urlRedirect + '/' + ui.item.id;
			}
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	}).data("autocomplete")._renderItem = function(ul, item) {
		return $("<li></li>").data("item.autocomplete", item).append(
		"<a>"+
			"<strong>" + item.label + "</strong>"+
			"<br />" +
		"<small>" + item.id_number + "<br />" + item.email + "</small>" +
			"</a>")
		.appendTo(ul);
	};
});