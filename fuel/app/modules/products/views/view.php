<?php
$participants = $product->get_participants_sorted();
?>

<div class="row">	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('session.name')?></div>			
			<div class="panel-body">
				<div class="well">
					<?=$product->notes?>
				</div>
				<dl>
					<dt><?=__('product.field.cost')?></dt>
					<dd>€ <?=$product->cost?></dd>
					<dt><?=__('product.field.paid_by')?></dt>
					<dd><?=$product->get_payer()->get_fullname() ?></dd>
				</dl>
			</div>
		</div>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<h3><?=__('product.field.participant_plural')?></h3>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th><?=__('user.field.name')?></th>
						<th><?=__('product.field.amount')?></th>
						<th><?=__('product.field.cost')?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($participants as $participant): ?>
					<tr>
						<td><?=$participant->user->get_fullname()?></td>
						<td><?=$participant->amount?></td>
						<td>€ <?=round(($product->cost / $product->count_total_participants()) * $participant->amount, 2)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>	
