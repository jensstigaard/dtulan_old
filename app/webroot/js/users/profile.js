$(document).ready(

	function() {

		$( ".tabs" ).tabs();

		function make_ajax_area(area_name){
			$area = $(area_name);
			$area_link = $area.find('a');
			$area.load($area_link.attr('href'));
		}

		make_ajax_area('.pizza_orders');
		make_ajax_area('.food_orders');
		make_ajax_area('.payments');
	}
	);