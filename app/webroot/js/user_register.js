$(document).ready(function(){
	$('#IdNumber').hide();
	$('#UserIdNumber').attr('type', 'hidden');

	$('#typeSelect').change(function(){
		var val = $(this).val();

		if(val == 'student'){
			$('#IdNumber').show();
			$('#UserIdNumber').attr('type', 'text');
		}
		else if(val == 'guest'){
			$('#IdNumber').hide();
			$('#UserIdNumber').attr('type', 'hidden');
		}
	});
	
	$('.tooltips').tooltip().click(function(event){
		event.preventDefault();
	});
});