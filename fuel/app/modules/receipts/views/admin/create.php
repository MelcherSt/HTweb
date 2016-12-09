<p>Create a receipt. Please select all sessions and/or product that should be settled with this receipt.</p>

<form method="post" action="/receipts/admin/create">
	<div class="form-group">
		<label for="comment">Notes</label>
		<textarea name="notes" class="form-control" rows="3" placeholder="Tell something about this receipt"></textarea>
	</div>

	<a class="btn btn-primary" onClick="checkAll()">Select all</a>
	<a class="btn btn-primary" onClick="uncheckAll()">Deselect all</a>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Date</th>
					<th>Participant(s)</th>
					<th>Cook(s)</th>
					<th>Dishwasher(s)</th>
					<th>Cost</th>
				</tr>
			</thead>
			<tbody>
			<?php 			
				foreach($sessions as $session): ?>
				<tr>
					<td>
						<label class="checkbox-inline">
							<input type="checkbox" class="session-select" name="sessions[]" value="<?=$session->id?>"> <?=$session->id?>
						</label>
					</td>
					<td><?=$session->date?></td>
					<td><?=$session->count_total_participants()?></td>
					<td><?=$session->count_cooks()?></td>
					<td><?=$session->count_dishwashers()?></td>
					<td><?=$session->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<button class="btn btn-success" type="submit" ><span class="fa fa-pencil-square-o"></span> Create receipt</button>
</form>	
		
<script>
function checkAll() {
	$(".session-select").attr('checked', true);
}

function uncheckAll() {
	$(".session-select").attr('checked', false);
}
</script>


