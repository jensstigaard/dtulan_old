$(document).ready(function(){
	$('#id_number').hide();

	$('#typeSelect').change(function(){
		var val = $(this).val();

		if(val == 'student'){
			$('#id_number').attr('type', 'text');
		}
		else if(val == 'guest'){
			$('#id_number').attr('type', 'hidden');
		}
	});
});