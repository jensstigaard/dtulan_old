$(document).ready(
	function() {

		$area = $('.ajax_area');
		$area_links = $area.find('thead a, div a');

		$area_links.click(function(event){
			event.preventDefault();

			$(this).closest('.ajax_area').load($(this).attr('href'));
			return false;
		});

	}
	);