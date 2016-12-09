<div class="row">
	<p>Here you find all your receipts.</p>
		<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Creation date</th>
					<th>Notes</th>
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

