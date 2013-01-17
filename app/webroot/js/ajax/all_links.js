$(document).ready(
	function() {

		$area = $('.ajax_area');
		$area_links = $area.find('a.load_inline, span.load_inline > a');

		$area_links.click(function(event){
			event.preventDefault();

			$(this).closest('.ajax_area').load($(this).attr('href'));
			return false;
		});

	}
	);