<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>



<div class="row">
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>

<h3><?=__('stats.next_cook_msg')?> <?=\Sessions\Controller_Stats_Api::_request_stats()['next_cook']['name']?></h3>
