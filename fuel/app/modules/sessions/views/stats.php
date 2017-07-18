<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div class="row">
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>

<h4><?=__('session.stats.next_cook_msg').': '.$next_cook?></h4>

<div class="row">
<!--	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">All-time</h3>
			</div>
			<div class="list-grou">
				<div class="list-group-item">
					<h4 class="list-group-item-heading">Heading</h4>
					<p class="list-group-item-text">Text here</p>
				</div>
			</div>
		</div>
	</div>-->
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">All time</h3>
			</div>
			<div class="list-grou">
				<div class="list-group-item">
					<h4 class="list-group-item-heading"></h4>
					<table class="table table-striped table-condensed">
						<thead>
							<td>Username</td>
							<td>Avg</td>
							<td>#</td>
						</thead>
						<tbody>	
							<?php foreach(\Sessions\Model_Session::cheapest_cook() as $user) {?>
								<tr>
									<Td><?=$user['username']?></td>
									<td><?=$user['avg_cost']?></td>
									<td><?=$user['cook_count']?></td>
								<tr>
							<?php } ?>
						</tbody>
					</table>
				
				</div>
			</div>
		</div>
	</div>
</div>