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

		$sweets_order = $("#sweets_order");

		$table_body = $sweets_order.find('table tbody');

		$last_row = $table_body.find('tr:last-child');

		$order_total = $last_row.find('.order_total');

		$order_buttons = $sweets_order.find('.order_buttons');

		var url_image_add = $('.hidden_images .image_add').attr('src');

		var url_image_remove = $('.hidden_images .image_remove').attr('src');


		$link = $('<a href="#"><img src="'+url_image_add+'" alt="" /></a>').click(
			function(event) {
				event.preventDefault();

				$item = $(this).closest("tr");
				$span = $(this).find('span');

				var title = $item.find(
					"td:first-child span").html();
				var desc = $item.find("td:first-child .description")
				.html();

				var price = $(this).parent().find(
					'span:not(".hidden")').text();

				var sweets_id = $(this).parent().find(
					'span.hidden.item_id').text();

				var item = {
					'sweets_id' : sweets_id,
					'title' : title,
					'desc' : desc,
					'price' : price
				};

				addItemToOrderList(item);
			});


		$sweets_orderable_span = $('table.sweets_list tbody tr td:last-child span');

		$sweets_orderable = $sweets_orderable_span.closest('td');

		$sweets_orderable.append($link);



		$(".text-order-submitted").hide();

		$(".order_submit").click(function(event){
			event.preventDefault();

			var lan_id = $(this).parent().find('div.hidden').text();

			if(orderListSize() > 0){
				$.post($(this).attr('href'), {
					'order_list': order_list,
					'lan_id': lan_id
				}, function(data) {
					if(data.trim()=='SUCCESS'){
						$sweets_order.find(".order_success").show().delay(2000).hide("slow");

						// Clear order visually
						clearOrder();

					// Update latest activities
					// showLatestActivities();
					}
					else{
						$sweets_order.find(".order_errors").text(data).show();
					}
				});
			}
			else{
				console.log('No items in order. ', orderListSize());
			}
			return false;
		});

		$order_buttons.find(".order_clear").click(function(event){
			event.preventDefault();
			clearOrder();
		});



		$delete_icon = $('<a href="#">' +
			'<img src="' + url_image_remove + '" alt="" />' +
			'</a>').click(function(event){
			event.preventDefault();

			$row = $(this).closest('tr');

//			console.log($row);

			var sweets_id	= $row.find('td:first-child').attr('class');
			var price = $row.find('td:nth-child(4)').text()/$row.find('td:first-child').text();

			decreaseTotal(price);
			order_list[sweets_id].quantity--;

			if(order_list[sweets_id].quantity>0){
				$row.find('td:first-child').text(order_list[sweets_id].quantity);
				$row.find('td:nth-child(4)').text(price * order_list[sweets_id].quantity);
			}
			else{
				delete order_list[sweets_id];
				removeItemFromOrderList(sweets_id);

				if(orderListSize()==0){
					// Hide text and submit-button
					$order_buttons.hide();
				}
			}
		});
	});


/* ADD A ITEM TO THE ORDER LIST */
function addItemToOrderList(item){

	if(order_list[item.id] == null){
		temp_item = {};
		temp_item.quantity = 0;
		temp_item.price = item.price;

		order_list[item.id] = temp_item;
	}

	if(!$order_buttons.is(':visible')){
		$order_buttons.show();
	}

	if(order_list[item.id].quantity<5){
		order_list[item.id].quantity++;
		increaseTotal(item.price);
	}

	if(order_list[item.id].quantity == 1){
		$row = $('<tr></tr>').addClass('order_item order_row_'+item.id).attr('title', item.desc);

		$('<td class="'+item.id+'">' + order_list[item.id].quantity + '</td>' +
			'<td>x</td>' +
			'<td>' + item.title +' <small>('+ item.desc +')</small></td>' +
			'<td>' + (item.price * order_list[item.id].quantity) +'</td>' +
			'<td>DKK</td>'  +
			'<td></td>'
			).appendTo($row);

		$delete_icon.clone(true, true).appendTo($row.find('td:last-child'));

		$last_row.before($row);
	}
	else if(order_list[item.id].quantity<=5){
		$table_body.find("tr.order_row_" + item.id + " td:first-child").text(order_list[item.id].quantity);
		$table_body.find("tr.order_row_" + item.id + " td:nth-child(4)").text((item.price * order_list[item.id].quantity));

		if(order_list[item.id].quantity<5)
			updateOrderRow(item.id);
	}
}

function updateOrderRow(id){
	var row = $sweets_order.find("tr.order_" + id);

	if(row.length==1)
		row.stop().hide().show(80);
}

function clearOrder(){

	// Arrays reset
	order_list = {};

	// Remove rows with item from order list
	$table_body.find("tr.order_item").remove();

	// Leave message
	window.onbeforeunload = null;

	// Set total to 0
	$order_total.text(0);

	// Hide text and submit-button
	$order_buttons.hide();
}


function removeItemFromOrderList(id){
	$table_body.find(".order_row_" + id).remove();
}

function decreaseTotal(price){
	$new_total = parseInt($order_total.text()) - parseInt(price);
	$order_total.text($new_total);
}

function increaseTotal(price){
	$new_total = parseInt($order_total.text()) + parseInt(price);
	$order_total.text($new_total);
}