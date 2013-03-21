$(document).ready(function() {
		
	var table = $('#pizza_order_list tbody');
	
	var link_mark_delivered = $('#pizza_order_list').attr('data-link-mark-delivered');
	var link_mark_errors = $('#pizza_order_list').attr('data-link-mark-errors');
	var link_user = $('#pizza_order_list').attr('data-link-user');
		
	function loadItemsInTable(items){
			
		table.empty();
		
		if(items.length == 0){
			table.append('<tr><td>No orders found</td></tr>');
		}
		else{
			
			$.each(items, function(){
			
				var class_status;
			
				if(this.PizzaOrder.status == 1){
					class_status = 'order_status_green';
				}
				else if(this.PizzaOrder.status == 2){
					class_status = 'order_status_red';
				}
				else{
					class_status = 'order_status_yellow';
				}
		
				$line = $('<tr>\n\
<td class="order_status '+class_status+'"></td>\n\
<td>\n\
<a href="' + link_user + '/' + this.User.id + '">' + this.User.name + '</a>\n\
<br />\n\
' + this.PizzaOrder.time_nice + '\n\
</td>\n\
<td>\n\
<ul class="order_items"></ul>\n\
</td>\n\
<td class="actions"></td>\n\
</tr>');
			
				$.each(this.PizzaOrderItem, function(){
					$line.find('ul.order_items').append('<li>' + this.quantity + ' x ' + this.PizzaPrice.Pizza.title + ' (' + this.PizzaPrice.PizzaType.title + ') (No. ' + this.PizzaPrice.Pizza.number + ')</li>');
				
				});
			
				if(this.PizzaOrder.status == 0){
					$('<a href="'+link_mark_delivered+'/'+this.PizzaOrder.id+'" class="btn btn-mini btn-success"><i class="icon-large icon-ok-sign"></i> Mark delivered</a>').appendTo($line.find('.actions'));
					$('<a href="'+link_mark_errors+'/'+this.PizzaOrder.id+'" class="btn btn-mini btn-danger"><i class="icon-large icon-exclamation-sign"></i> With errors</a>').appendTo($line.find('.actions'));
				}
			
				$line.appendTo(table);
			});
		}
	}
	
	
	var form = $("#search_pizza_orders");
	var pizza_wave_id = form.attr('data-pizza-wave-id');
	var url = form.attr('action');
	
	$.ajax({
		type: 'GET',
		url: url,
		data: {
			pizza_wave_id: pizza_wave_id
		},
		dataType: 'json'
	}).success(function(data){
		loadItemsInTable(data.pizza_orders);
	});
	

	
	form.submit(function(e){
		e.preventDefault();
		return false;
	});
	
	form.find(':input').change(function() {
		
		var search_user = form.find('input[type=text]').val();
		var search_only_not_delivered = form.find('input[type=checkbox]').prop('checked');
		
		
		$.ajax({
			type: 'GET',
			url: url,
			data: {
				pizza_wave_id: pizza_wave_id,
				user: search_user,
				only_not_delivered: search_only_not_delivered
			},
			dataType: 'json'
		}).success(function(data){
			loadItemsInTable(data.pizza_orders);
		});
	});
});
