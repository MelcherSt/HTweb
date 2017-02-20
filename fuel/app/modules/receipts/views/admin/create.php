<p>Create a receipt. Please select all sessions and/or products that should be settled in this receipt.</p>

<form method="post" action="/receipts/admin/create">
	<div class="form-group">
		<label for="comment"><?=__('receipt.field.notes')?></label>
		<textarea name="notes" class="form-control" rows="3" placeholder="Tell something about this receipt"></textarea>
	</div>

	<h2><?=__('session.title_admin')?></h2>
	<div class="btn-group btn-group-sm">
		<a class="btn btn-primary" onClick="checkAllSessions()"><?=__('actions.select_all')?></a>
		<a class="btn btn-primary" onClick="uncheckAllSessions()"><?=__('actions.deselect_all')?></a>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th><?=__('session.field.date')?></th>
					<th><?=__('session.role.participant_plural')?></th>
					<th><?=__('session.role.cook_plural')?></th>
					<th><?=__('session.role.dishwasher_plural')?></th>
					<th><?=__('product.field.cost')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sessions as $session) { ?>
				<tr class="clickable-row" data-href="/sessions/view/<?=$session->date?>">
					<td>
						<label class="checkbox-inline">
							<input type="checkbox" class="session-select" name="sessions[]" value="<?=$session->id?>"> <?=$session->id?>
						</label>
					</td>
					<td><?=$session->date?></td>
					<td><?=$session->count_total_participants()?></td>
					<td><?=$session->count_cooks()?></td>
					<td><?=$session->count_dishwashers()?></td>
					<td>€ <?=$session->cost?></td>
				</tr>
				<?php } ?>
			</tbody>
			<em><?=sizeof($sessions) == 0 ? __('session.empty_list') : ''?></em>
		</table>
	</div>
	
	<h2><?=__('product.title_admin')?></h2>
	<div class="btn-group btn-group-sm">
		<a class="btn btn-primary" onClick="checkAllProducts()"><?=__('actions.select_all')?></a>
		<a class="btn btn-primary" onClick="uncheckAllProducts()"><?=__('actions.deselect_all')?></a>
	</div>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th><?=__('product.field.name')?></th>
					<th><?=__('product.field.date')?></th>		
					<th><?=__('product.field.paid_by')?></th>
					<th><?=__('product.field.participant_plural')?></th>
					<th><?=__('product.field.cost')?></th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($products as $product) { ?>
				<tr class="clickable-row" data-href="/products/view/<?=$product->id?>">
					<td>
						<label class="checkbox-inline">
							<input type="checkbox" class="product-select" name="products[]" value="<?=$product->id?>"> <?=$product->id?>
						</label>
					</td>
					<td><?=$product->name?></td>
					<td><?=date('Y-m-d', $product->created_at)?></td>
					<td><?=$product->get_payer()->name?></td>
					<td><?=$product->get_nicified_participants()?></td>
					<td>€ <?=$product->cost?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
	</div>
	
	<button class="btn btn-primary" type="submit" ><span class="fa fa-pencil-square-o"></span> Create receipt</button>
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


