$(document).ready(function(){
	
	var area = '#chart-user-register';
	
	$area = $(area);

	var margin = {
		top: 20, 
		right: 20, 
		bottom: 30, 
		left: 40
	},
	width = 600 - margin.left - margin.right,
	height = 500 - margin.top - margin.bottom;

	var formatY = d3.format("d");

	var x = d3.scale.ordinal()
	.rangeRoundBands([0, width], .1);

	var y = d3.scale.linear()
	.range([height, 0]);

	var xAxis = d3.svg.axis()
	.scale(x)
	.orient("bottom");

	var yAxis = d3.svg.axis()
	.scale(y)
	.orient("left")
	.tickFormat(formatY);

	var svg = d3.select(area).append("svg")
	.attr("width", width + margin.left + margin.right)
	.attr("height", height + margin.top + margin.bottom)
	.append("g")
	.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

	d3.json($area.attr('data-source-url'), function(error, data) {
		
		data.forEach(function(d) {
			d.count = +d.count;
		});

		x.domain(data.map(function(d) {
			return d.dato;
		}));
		y.domain([0, d3.max(data, function(d) {
			return d.count;
		})]);

		svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis);

		svg.append("g")
		.attr("class", "y axis")
		.call(yAxis)
		.append("text")
		.attr("transform", "rotate(-90)")
		.attr("y", 6)
		.attr("dy", ".71em")
		.style("text-anchor", "end")
		.text("New users");

		svg.selectAll(".bar")
		.data(data)
		.enter().append("rect")
		.attr("class", "bar")
		.attr("x", function(d) {
			return x(d.dato);
		})
		.attr("width", x.rangeBand())
		.attr("y", function(d) {
			return y(d.count);
		})
		.attr("height", function(d) {
			return height - y(d.count);
		});

	});

});

