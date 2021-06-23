if ($('#ordersTotalChart').length != 0) {

	var splittedLabels = [];
	var splittedValues = [];

	$.ajax({
		url: '/orderTotal',
		type: 'GET',
		success: function (data) {
			data.forEach(element => {
				splittedLabels.push(element.id.substring(0, 16).concat('...'));
				splittedValues.push(element.total);
			});
			dataDailySalesChart = {
				labels: splittedLabels,
				series: [splittedValues]
			};

			optionsDailySalesChart = {
				lineSmooth: Chartist.Interpolation.cardinal({
					tension: 0
				}),
				low: 0,
				high: 300, // set the high sa the biggest value + something 
				chartPadding: {
					top: 0,
					right: 0,
					bottom: 0,
					left: 0
				},
			}
			var dailySalesChart = new Chartist.Line('#ordersTotalChart', dataDailySalesChart, optionsDailySalesChart);
		}
	});
}

$.ajax({
	url: '/orderCount',
	type: 'GET',
	success: function (data) {
		$("#numOrders").text("Total Orders #: " + data[0].countOrder);
	}
});

$.ajax({
	url: '/productCount',
	type: 'GET',
	success: function (data) {
		$("#numProducts").text("Total Products #: " + data[0].countProduct);
	}
});
