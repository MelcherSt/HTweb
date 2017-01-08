<p>Create a receipt. Please select all sessions and/or product that should be settled with this receipt.</p>

<form method="post" action="/receipts/admin/create">
	<div class="form-group">
		<label for="comment">Notes</label>
		<textarea name="notes" class="form-control" rows="3" placeholder="Tell something about this receipt"></textarea>
	</div>

	<h2><?=__('session.title_admin')?></h2>
	<div class="btn-group btn-group-sm">
		<a class="btn btn-primary" onClick="checkAllSessions()">Select all</a>
		<a class="btn btn-primary" onClick="uncheckAllSessions()">Deselect all</a>
	</div>
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
	
	<h2><?=__('product.title_admin')?></h2>
	<div class="btn-group btn-group-sm">
		<a class="btn btn-primary" onClick="checkAllProducts()">Select all</a>
		<a class="btn btn-primary" onClick="uncheckAllProducts()">Deselect all</a>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Payer</th>
					<th>Participants</th>
					<th>Cost</th>
				</tr>
			</thead>
			<tbody>
			<?php 			
				foreach($products as $product): ?>
				<tr>
					<td>
						<label class="checkbox-inline">
							<input type="checkbox" class="session-select" name="sessions[]" value="<?=$product->id?>"> <?=$product->id?>
						</label>
					</td>
					<td><?=$product->name?></td>
					<td><?=$product->payer->name?></td>
					<td><?=$product->count_participants()?></td>
					<td><?=$session->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<button class="btn btn-success" type="submit" ><span class="fa fa-pencil-square-o"></span> Create receipt</button>
</form>	
		
<script>
function checkAllSessions() {
	$(".session-select").prop('checked', true);
}

function uncheckAllSessions() {
	$(".session-select").prop('checked', false);
}

function checkAllProducts() {
	$(".product-select").prop('checked', true);
}

function uncheckAllProducts() {
	$(".product-select").prop('checked', false);
}
</script>


