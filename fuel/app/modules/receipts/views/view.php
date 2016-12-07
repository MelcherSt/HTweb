<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
	$(function() {
	
	Morris.Bar({
		element: 'bar-example',
		data: [
			<?php foreach($receipt->users as $user_receipt): ?>
			{
				y: '<?=$user_receipt->user->get_fullname()?>',
				a: '<?=$user_receipt->points?>',
				b: '<?=$user_receipt->balance?>'
			},
			<?php endforeach; ?>
		],
		xkey: 'y',
		ykeys: ['a', 'b'],
		labels: ['Points', 'Balance in €']
	  });
    
});
</script>

<p><?=$receipt->notes?></p>

<div id="bar-example" style="height: 250px;"></div>
<div class="row">
	<h2>Users</h2>	
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
				<?php foreach($receipt->users as $user_receipt): ?>
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

<div class="row">
	<h2>Transaction schema</h2>
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

<div class="row">
	<h2>Products</h2>
	<p>The following products have been included in this receipt.</p>
</div>