$(document).ready(function(){
	$('#sponsors a, #header a').hover(function(){
		$(this).find('img').stop().fadeTo(500, 1);
	}, function(){
		$(this).find('img').stop().fadeTo(800, 0.5);
	});
});