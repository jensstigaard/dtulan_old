$(document).ready(

	function() {

		$( ".tabs" ).tabs();

		function make_ajax_area(area_name){
			$area = $(area_name);
			$area_link = $area.find('a');
			$area.load($area_link.attr('href'));
		}

		make_ajax_area('.lan_signups');
		make_ajax_area('.lan_signups_crew');
		make_ajax_area('.tournaments');
	}
	);