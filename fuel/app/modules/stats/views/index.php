<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>



<div class="row">
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>


<script>
	// Create a Bar Chart with Morris
  var chart = Morris.Bar({
    // ID of the element in which to draw the chart.
    element: 'distr-bar-chart',
    data: [0,0], // Set initial data (ideally you would provide an array of default data)
    xkey: 'name', // Set the key for X-axis
    ykeys: ['points'], // Set the key for Y-axis
    labels: ['Points'] // Set the label when bar is rolled over
  });

  // Fire off an AJAX request to load the data
  $.ajax({
      type: "GET",
      dataType: 'json',
      url: "/stats/api", // This is the URL to the API
    })
    .done(function( data ) {
      // When the response to the AJAX request comes back render the chart with new data
      chart.setData(data);
    });
</script>

<h3><?=__('stats.next_cook_msg')?> <?=\Stats\Controller_Api::_request_stats()['next_cook']['name']?></h3>
