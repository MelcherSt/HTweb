<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div class="well"><?=$receipt->notes?></div >

<div class="row">
	<h2>Cost and points distrubution</h2>
	<p>A receipt shows the distribution of cost and points over an interval. 
		This means that only the points gained or lost in the given interval
		will be shown in the chart and table below.</p>
	<div id="distr-bar-chart" style="height: 250px;"></div>
	
	<h3>Detailed overview</h3>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>Points delta</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				
				$point_checksum = 0;
				$balance_checksum = 0;
				
				
				foreach($receipt->get_users_sorted() as $user_receipt): 
					
					$point_checksum += $user_receipt->points;
					$balance_checksum += $user_receipt->balance;
					?>
				<tr>
					<td><?=$user_receipt->user->get_fullname()?></td>
					<td><?=$user_receipt->points?></td>
					<td><?='€ ' . $user_receipt->balance; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<h4>Validation</h4>
	<div>
		<?php if($point_checksum == 0) {?>
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

<?php $schema = $receipt->get_transaction_schema();?>
<br>
<div class="row">
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
</div>

<div class="row">
	<h2>Sessions</h2>
	<p>The following sessions have been included in this receipt.</p>
		<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Date</th>
					<th>Participants</th>
					<th>Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($receipt->sessions as $session_receipt): 
					$session = $session_receipt->session;?>
				<tr>
					<td><?=$session->id?></td>
					<td><?=$session->date?></td>
					<td><?=$session->count_total_participants()?></td>
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