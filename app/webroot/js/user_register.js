$(document).ready(function(){
	//$('#id_number');

	$('#typeSelect').change(function(){
		var val = $(this).val();

		if(val == 'student'){
			$('#UserIdNumber').attr('type', 'text').show();
		}
		else if(val == 'guest'){
			$('#UserIdNumber').attr('type', 'hidden').hide();
		}
	});
});