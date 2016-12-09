<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>



<div class="row">
	<div class="well"><?=$receipt->notes?></div>
	
	<h2>Cost and points distrubution</h2>
	<p>A receipt shows the distribution of cost and points over an interval. 
		This means that only the points gained or lost in the given interval
		will be shown in the chart and table below.</p>
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>

<div class="row">
	<div class="col-md-6"> 
		<h3>Detailed overview</h3>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>∆ Points</th>
						<th>Balance</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($receipt->get_users_sorted() as $user_receipt): ?>
					<tr>
						<td><?=$user_receipt->user->get_fullname()?></td>
						<td><?=$user_receipt->points?></td>
						<td><?='€ ' . $user_receipt->balance; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		
	</div>
	
	<?php $schema = $receipt->get_transaction_schema();?>
	
	<div class="col-md-6">
		<h3>Transaction schema</h3>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>From</th>
						<th>Amount</th>

						<th>To</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($schema as $key => $items): ?>
					<tr>
						<td><?=\Model_User::find($items[0])->get_fullname()?></td>
						<td><?= '€ ' . $items[2]?></td>
						<td><?=\Model_User::find($items[1])->get_fullname()?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		
		<div class="pull-right">
			<?php 
			$points_checksum = $receipt->validate_points();
			$balance_checksum = $receipt->validate_balance();
			
			if($points_checksum == 0) {?>
			<p class="label label-success"><span class="fa fa-check"></span> Points checksum</p>
			<?php } else { ?>
			<p class="label label-danger"><span class="fa fa-times"></span> Points checksum (<?=$points_checksum?>)</p>
			<?php } ?>

			<?php if($balance_checksum == 0) {?>
			<p class="label label-success"><span class="fa fa-check"></span> Balance checksum</p>
			<?php } else { ?>
			<p class="label label-danger"><span class="fa fa-times"></span> Balance checksum (<?=$balance_checksum?>)</p>
			<?php } ?>
		</div>
	</div>
</div>	


<div class="row">
	<h2>Sessions</h2>
	<p>The following sessions have been included in this receipt.</p>
		<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th>Participants</th>
					<th>Cook(s)</th>
					<th>Dishwasher(s)</th>
					<th>Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($receipt->sessions as $session_receipt): 
					$session = $session_receipt->session;?>
				<tr class="clickable-row" data-href="/sessions/view/<?=$session->date?>">
					<td><?=$session->date?></td>
					<td><?=$session->count_total_participants()?></td>
					<td>
						<?php foreach($session->get_cook_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>	
					</td>
					<td>
						<?php foreach($session->get_dishwasher_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>
					</td>
					<td><?='€ ' . $session->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>


<script>
	$(function() {
	
	Morris.Bar({
		element: 'distr-bar-chart',
		data: [
			<?php foreach($receipt->get_users_sorted() as $user_receipt): ?>
			{
				y: '<?=$user_receipt->user->get_fullname()?>',
				a: '<?=$user_receipt->points?>',
				b: '<?=$user_receipt->balance?>'
			},
			<?php endforeach; ?>
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Points', 'Balance']
	  });
    
});
</script>