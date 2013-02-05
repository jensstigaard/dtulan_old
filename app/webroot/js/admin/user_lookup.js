$(function() {

	$user_lookup = $('#user_lookup_input');
	
	//	$input.typeahead({
	//		source: function (query, typeahead) {
	//
	//			return $.ajax({
	//				url: $input.attr('data-link'),
	//				type : "post",
	//				dataType: "json",
	//				async: true,
	//				data: {
	//					search_startsWith: query
	//				},
	//				success: function (data) {
	//					return typeahead(data);
	//				}
	//			});
	//		},
	//		onselect: function(item) {
	//			document.location = item.id;
	//		},
	//		property: 'name'
	//	});

	//	$input = $user_lookup.find('#user_lookup_input');
	
	$user_lookup.autocomplete({
		minLength: 2,
		source: function( request, response ) {
		$.ajax({
			url: $user_lookup.attr('data-link'),
			dataType: "json",
			type : 'POST',
			data: {
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
		selectFirst : true,
		select: function( event, ui ) {
		if(ui.item){
		document.location = $user_lookup.attr('data-redirect') + '/' + ui.item.id;
		}
			
		return false;
		}
		})
	.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $("<li></li>").data("item.autocomplete", item).append(
			"<a>" +
			"" +
			"<strong>" + item.label + "</strong>" +
			"<br />" +
			"<small>" + item.id_number + "<br />" + item.email + "</small>" +
			"</a>")
		.appendTo(ul);
	};
});