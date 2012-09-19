var order_list = {};


//Thanks to John Resig - http://ejohn.org/blog/fast-javascript-maxmin/
Array.max = function(array){
	var narray = [];

	for ( var i = 0; i < array.length; i++ )
		if ( array[i] != null )
			narray.push( array[i] );
	return Math.max.apply( Math, narray );
};

function orderListSize(){
	var count=0;

	for(var i in order_list) {
		if (order_list.hasOwnProperty(i)) {
			count++;
		}
	}

	return count;
}


$(document).ready(
	function() {

		$pizza_order = $("#pizza_order");

		$table_body = $pizza_order.find('table tbody');

		$last_row = $table_body.find('tr:last-child');

		$pizza_order_total = $last_row.find('.pizza_order_total');

		$pizza_order_buttons = $pizza_order.find('.pizza_order_buttons');

		var url_image_add = $('.hidden_images .image_add').attr('src');

		var url_image_remove = $('.hidden_images .image_remove').attr('src');


		$link = $('<a href="#"><img src="'+url_image_add+'" alt="" /></a>').click(
			function(event) {
				event.preventDefault();

				$item = $(this).closest("tr");
				$span = $(this).find('span');

				var title = $item.find(
					"td.title").html();
				var desc = $item.find("td.desc")
				.html();

				var price_value = $(this).parent().find(
					'span:not(".hidden")').html();

				var price_id = $(this).parent().find(
					'span.hidden.price_id').html();

				var column = $(this).closest('tr').children().index($(this).parent());

				//				console.log('column:' + column);

				$type_info = $item.closest('table').find('thead tr th:nth-child('+(column-1)+')');

				var type_title = $type_info.text();

				var pizza = {
					'price_id' : price_id,
					'title' : title,
					'desc' : desc,
					'price_value' : price_value,
					'type_value' : type_title
				};

				addPizzaToOrderList(pizza);
			});


		$pizzas_orderable_span = $('table.pizza_list tr.pizza_item td.price span.available');

		$pizzas_orderable = $pizzas_orderable_span.closest('td');

		$pizzas_orderable.append($link);



		$(".text-order-submitted").hide();

		$(".pizza_order_submit").click(function(event){
			event.preventDefault();

			var wave_id = $(this).parent().find('div.hidden').text();

			if(orderListSize() > 0){
				$.post($(this).attr('href'), {
					'order_list': order_list,
					'wave_id': wave_id
				}, function(data) {
					if(data.trim()=='SUCCESS'){
						$pizza_order.find(".pizza_order_success").show().delay(2000).hide("slow");

						// Clear order visually
						clearOrder();

					// Update latest activities
					// showLatestActivities();
					}
					else{
						$pizza_order.find(".pizza_order_errors").text(data).show();
					}
				});
			}
			else{
				console.log('No pizzas in order. ', orderListSize());
			}
			return false;
		});

		$pizza_order_buttons.find(".pizza_order_clear").click(function(event){
			event.preventDefault();
			clearOrder();
		});



		$delete_icon = $('<a href="#">' +
			'<img src="' + url_image_remove + '" alt="" />' +
			'</a>').click(function(event){
			event.preventDefault();

			$row = $(this).closest('tr');

			console.log($row);

			var price_id	= $row.find('td:first-child').attr('class');
			var price_value = $row.find('td:nth-child(4)').text()/$row.find('td:first-child').text();

			decreaseTotal(price_value);
			order_list[price_id].quantity--;

			if(order_list[price_id].quantity>0){
				$row.find('td:first-child').text(order_list[price_id].quantity);
				$row.find('td:nth-child(4)').text(price_value * order_list[price_id].quantity);
			}
			else{
				delete order_list[price_id];
				removePizzaFromOrderList(price_id);

				if(orderListSize()==0){
					// Hide text and submit-button
					$pizza_order_buttons.hide();
				}
			}
		});
	});


/* ADD A PIZZA TO THE ORDER LIST */
function addPizzaToOrderList(pizza){

	if(order_list[pizza.price_id] == null){
		temp_pizza = {};
		temp_pizza.quantity = 0;
		temp_pizza.price_value = pizza.price_value;

		order_list[pizza.price_id] = temp_pizza;
	}

	if(!$pizza_order_buttons.is(':visible')){
		$pizza_order_buttons.show();
	}

	if(order_list[pizza.price_id].quantity<5){
		order_list[pizza.price_id].quantity++;
		increaseTotal(pizza.price_value);
	}

	if(order_list[pizza.price_id].quantity == 1){
		$row = $('<tr></tr>').addClass('pizza_order_item pizza_order_row_'+pizza.price_id).attr('title', pizza.desc);

		$('<td class="'+pizza.price_id+'">' + order_list[pizza.price_id].quantity + '</td>' +
			'<td>x</td>' +
			'<td>' + pizza.title +' <small>('+ pizza.type_value +')</small></td>' +
			'<td>' + (pizza.price_value * order_list[pizza.price_id].quantity) +'</td>' +
			'<td>DKK</td>'  +
			'<td></td>'
			).appendTo($row);

		$delete_icon.clone(true, true).appendTo($row.find('td:last-child'));

		$last_row.before($row);
	}
	else if(order_list[pizza.price_id].quantity<=5){
		$table_body.find("tr.pizza_order_row_" + pizza.price_id + " td:first-child").text(order_list[pizza.price_id].quantity);
		$table_body.find("tr.pizza_order_row_" + pizza.price_id + " td:nth-child(4)").text((pizza.price_value * order_list[pizza.price_id].quantity));

		if(order_list[pizza.price_id].quantity<5)
			updatePizzaOrderRow(pizza.price_id);
	}
}

function updatePizzaOrderRow(pizza_price_id){
	var row = $pizza_order.find("tr.pizza_order_" + pizza_price_id);

	if(row.length==1)
		row.stop().hide().show(80);
}

function clearOrder(){

	// Arrays reset
	order_list = {};

	// Remove rows with pizzas from order list
	$table_body.find("tr.pizza_order_item").remove();

	// Leave message
	window.onbeforeunload = null;

	// Set total to 0
	$pizza_order_total.text(0);

	// Hide text and submit-button
	$pizza_order_buttons.hide();
}


function removePizzaFromOrderList(pizza_price_id){
	$table_body.find(".pizza_order_row_" + pizza_price_id).remove();
}

function decreaseTotal(price){
	$new_total = parseInt($pizza_order_total.text()) - parseInt(price);
	$pizza_order_total.text($new_total);
}

function increaseTotal(price){
	$new_total = parseInt($pizza_order_total.text()) + parseInt(price);
	$pizza_order_total.text($new_total);
}