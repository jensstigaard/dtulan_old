$(document).ready(function(){

	toggleElements($('#command').val());

	$('#command').change(function(){
		var val = $(this).val();

		toggleElements(val);
	});

	function toggleElements(val){
		if(val == 'uri'){
			$('#command_value').show();
			$('#text').hide();
		}
		else if(val == 'text'){
			$('#command_value').hide();
			$('#text').show();
		}
	}
});