$(document).ready(function(){
	
	$countdown = $('#countdown-lan');
	
	var countdown_date = new Date($countdown.text());
	
	$('#countdown-lan').countdown({ 
		until: countdown_date,
		format: 'DHMS'
	//			serverSync: serverTime
	}); 
});
			
function serverTime() { 
	var time = null; 
	$.ajax({
		url: 'http://myserver.com/serverTime.php', 
		async: false, 
		dataType: 'text', 
		success: function(text) { 
			time = new Date(text); 
		}, 
		error: function(http, message, exc) { 
			time = new Date(); 
		}
	}); 
	return time;
}