<?php $participants = $product->get_participants_sorted(); ?>
<h3><?=__('product.field.participant_plural')?></h3>
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?=__('user.field.name')?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($participants as $participant): ?>
			<tr>
				<td><?=$participant->user->get_fullname()?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>
