$(document).ready(function(){
	$('.pizza_list').accordion({
			autoHeight: false,
			navigation: true,
			collapsible: true,
			active: false,
			animated: false,
			header: "h3"
		}).disableSelection();
});