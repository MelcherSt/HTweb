<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

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

<div class="row">
	<div class="well"><?=$receipt->notes?></div>
	
	<h2><?=__('receipt.view.title')?></h2>
	<p><?=__('receipt.view.msg')?></p>
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>

<div class="row">
	<div class="col-md-6"> 
		<h3><?=__('receipt.view.detail')?></h3>
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
		<h3><?=__('receipt.view.trans_schema.title')?></h3>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?=__('receipt.view.trans_schema.from')?></th>
						<th><?=__('receipt.view.trans_schema.amount')?></th>
						<th><?=__('receipt.view.trans_schema.to')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($schema as $key => $items): 
						$to_user = \Model_User::find($items[1]);
						
					?>
					<tr>
						<td><?=\Model_User::find($items[0])->get_fullname()?></td>
						<td><?= '€ ' . $items[2]?></td>
						<td><?=$to_user->get_fullname()?> - <?=$to_user->iban?></td>
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
			<p class="label label-success"><span class="fa fa-check"></span> <?=__('receipt.view.point_check')?></p>
			<?php } else { ?>
			<p class="label label-danger"><span class="fa fa-times"></span> <?=__('receipt.view.point_check')?> (<?=$points_checksum?>)</p>
			<?php } ?>

			<?php if($balance_checksum == 0) {?>
			<p class="label label-success"><span class="fa fa-check"></span> <?=__('receipt.view.balance_check')?></p>
			<?php } else { ?>
			<p class="label label-danger"><span class="fa fa-times"></span> <?=__('receipt.view.balance_check')?> (<?=$balance_checksum?>)</p>
			<?php } ?>
		</div>
	</div>
</div>	


<div class="row">
	<h2><?=__('session.name_plural')?></h2>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th><?=__('session.field.date')?></th>
					<th><?=__('session.role.participant_plural')?></th>
					<th><?=__('session.role.cook_plural')?></th>
					<th><?=__('session.role.dishwasher_plural')?></th>
					<th><?=__('session.field.cost')?></th>
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
	
	<h2><?=__('product.name_plural')?></h2>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th><?=__('product.field.name')?></th>
					<th><?=__('product.field.date')?></th>		
					<th><?=__('product.field.paid_by')?></th>
					<th><?=__('product.view.participant_plural')?></th>
					<th><?=__('product.field.cost')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($receipt->products as $product_receipt): 
					$product = $product_receipt->product;?>
				<tr class="clickable-row" data-href="/products/view/<?=$product->id?>">
					<td><?=$product->name?></td>
					<td><?=date('Y-m-d', $product->created_at)?></td>
					<td><?=$product->payer->get_fullname()?>
					<td><?=$product->count_participants()?></td>
					<td><?='€ ' . $product->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>


