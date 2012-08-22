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

		$pizza_order = $(".pizza_order");

		$pizza_order_total = $(".pizza_order").find('.pizza_order_total');


		$link = $('<a href="#">+</a>').click(
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

				$type_info = $item.parent().find('tr th:nth-child('+(column-1)+')');

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


		$pizzas_orderable_span = $('table.pizza_list tr.pizza_item td.price span');

		$pizzas_orderable = $pizzas_orderable_span.closest('td');

		$pizzas_orderable.append($link);




		$(".text-order-submitted").hide();

		$(".submit_order").click(function(event){
			event.preventDefault();

			var wave_id = $(this).attr('alt');

			if(orderListSize() > 0){
				$.post("ajax/pizza_add_order.php", {
					'order_list': order_list,
					'wave_id': wave_id
				}, function(data) {
					if(data==='1'){
						$(".pizza_order .text-order-submitted" ).show().delay(2000).hide("slow");

						// Clear order visually
						clearOrder();

						// Update latest activities
						showLatestActivities();
					}
					else{
						console.log('Order list: ' + order_list);
						console.log('DATA: ' + data);
					}
				});
			}
			else{
				console.log(OrderListSize());
			}
			return false;
		});

		$(".clear_order").click(function(event){
			event.preventDefault();
			clearOrder();
		});
	});


/* ADD A PIZZA TO THE ORDER LIST */
function addPizzaToOrderList(pizza){

	console.log(pizza);

	if(order_list[pizza.price_id] == null){
		temp_pizza = {};
		temp_pizza.amount = 0;
		temp_pizza.price_value = pizza.price_value;

		order_list[pizza.price_id] = temp_pizza;
	}

	console.log('Order list: ', order_list);
	//	console.log('Size of orderlist:' , orderListSize());

	if(!$pizza_order.is(':visible')){
		$pizza_order.stop(true, true).show('fadein');
	//		window.onbeforeunload = "Are you sure you want to leave?";
	}

	if(order_list[pizza.price_id].amount<5){
		order_list[pizza.price_id].amount++;
		increaseTotal(pizza.price_value);
	}

	if(order_list[pizza.price_id].amount == 1){
		$row = $('<tr></tr>').addClass('pizza_order_item pizza_order_' + pizza.price_id).attr('title', pizza.desc);

		$('<td>' + order_list[pizza.price_id].amount + '</td>' +
			'<td>x</td>' +
			'<td>' + pizza.title +' <small>('+ pizza.type_value +')</small></td>' +
			'<td>' + (pizza.price_value * order_list[pizza.price_id].amount) +'</td>' +
			'<td>DKK</td>'  +
			'<td></td>'  +
			'').appendTo($row);

		var $link = $('<a href="#">' +
			'<img src="img/16x16_GIF/action_remove.gif" alt="" />' +
			'</a>').click(function(event){
			event.preventDefault();

			decreaseTotal(pizza.price_value);
			order_list[pizza.price_id].amount--;

			if(order_list[pizza.price_id].amount>0){
				$row.find('td:nth-child(1)').text(order_list[pizza.price_id].amount);
				$row.find('td:nth-child(4)').text(pizza.price_value * order_list[pizza.price_id].amount);
			}
			else{
				delete order_list[pizza.price_id];
				removePizzaFromOrderList(pizza.price_id);

				if(orderListSize() == 0)
					$pizza_order.stop(true, true).hide('fadeout');
			}
		}).appendTo($row.find('td:last-child'));

		$table = $pizza_order.find('table tr:last-child').before($row);

		$link.appendTo($row.find('td:last-child'));

		$link.addClass('remove_pizza');
	}
	else if(order_list[pizza.price_id].amount<=5){
		$pizza_order.find("tr.pizza_order_" + pizza.price_id + " td:first-child").text(order_list[pizza.price_id].amount);
		$pizza_order.find("tr.pizza_order_" + pizza.price_id + " td:nth-child(4)").text((pizza.price_value * order_list[pizza.price_id].amount));

		if(order_list[pizza.price_id].amount<5)
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
	$pizza_order.find("table tr.pizza_order_item").remove();

	// Hide order-box
	//	$pizza_order.delay(1000).hide("slow");

	// Leave message
	window.onbeforeunload = null;

	// Set total to 0
	$pizza_order_total.text(0);

	// Hide text and submit-button
	$(".order_buttons").hide().delay(2000).show();
}


function removePizzaFromOrderList(pizza_price_id){
	$pizza_order.find(".pizza_order_" + pizza_price_id).remove();
}

function decreaseTotal(price){
	$new_total = parseInt($pizza_order_total.text()) - parseInt(price);
	$pizza_order_total.text($new_total);
}

function increaseTotal(price){
	$new_total = parseInt($pizza_order_total.text()) + parseInt(price);
	$pizza_order_total.text($new_total);
}