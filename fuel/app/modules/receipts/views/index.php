<div class="row">
	<p><?=__('receipt.index.msg')?></p>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th><?=__('receipt.field.date')?></th>
					<th><?=__('receipt.field.notes')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($receipts as $receipt): ?>
				<tr class="clickable-row" data-href="/receipts/view/<?=$receipt->id?>">
					<td><?=$receipt->date?></td>
					<td><?=$receipt->notes?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

