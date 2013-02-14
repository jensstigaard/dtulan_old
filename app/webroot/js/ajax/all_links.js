$(document).ready(
	function() {

		$area = $('.ajax_area');
		$area_links = $area.find('a.load_inline, li.load_inline > a');

		$area_links.click(function(event){
			event.preventDefault();

			$(this).closest('.ajax_area').load($(this).attr('href'));
			return false;
		});
	}
	);