<?php $participants = $product->get_participants_sorted(); ?>
<h3><?=__('product.field.participant_plural')?></h3>
<div class="table-responsive">
	<table class="table table-hover">
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
