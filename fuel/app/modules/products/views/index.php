<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" onClick="showAddModal()" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?=__('product.index.btn.add_product')?></a>	
			</div>
		</div>
		
		<?=Request::forge('/privileges/products/nav')->execute();?>
		
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<p><?=__('product.index.msg')?> <a href="/receipts"><?=__('receipt.title')?></a>.</p>


		<h4><?=__('product.index.paid_by_me')?></h4>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('product.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.date')?></th>		
						<th class="col-md-2"><?=__('product.field.participant_plural')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
						<th class="col-md-2"><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$products_paid = \Products\Model_Product::get_by_payer($current_user->id);
					foreach($products_paid as $product): ?>
					<tr class="clickable-row" data-href="/products/view/<?=$product->id?>">
						<td><?=$product->name?></td>
						<td><?=date('Y-m-d', $product->created_at)?></td>
						<td><?=$product->get_nicified_participants()?></td>
						<td><?='€ ' . $product->cost?></td>
						<td><a href="#" data-href="#" class="clickable-row" onclick="showDeleteModal(<?=$product->id?>, '<?=e($product->name)?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<em><?=sizeof($products_paid) == 0 ? __('product.empty_list') : ''?></em>
		</div>
		
		<h4><?=__('product.index.paid_for_me')?></h4>
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('product.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.date')?></th>	
						<th class="col-md-2"><?=__('product.field.paid_by')?></th>
						<th class="col-md-2"><?=__('product.field.participant_plural')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$products = \Products\Model_Product::get_by_user($current_user->id);
					foreach($products as $product): ?>
					<tr class="clickable-row" data-href="/products/view/<?=$product->id?>">
						<td><?=$product->name?></td>
						<td><?=date('Y-m-d', $product->created_at)?></td>
						<td><?=$product->get_payer()->get_fullname()?>
						<td><?=$product->get_nicified_participants()?></td>
						<td><?='€ ' . $product->cost?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
		</div>
	</div>
</div>

<!-- Modal dialog for product deletion -->
<div id="delete-product-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/products/delete/" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.remove.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.remove.msg')?> <strong><span id="delete-product-name"></span></strong>?</p>
					<div class="form-group">
						<input id="delete-product-id" type="hidden" class="form-control" name="product_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="<?=__('product.modal.remove.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal dialog for product creation -->
<div id="add-product-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="add-product" action="/products/create/" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.create.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.create.msg')?></p>					
					
					<div class="form-group">
						<label for="name"><?=__('product.field.name')?></label>
						<input name="name" class="form-control" required></input>
					</div>
					
					<div class="form-group">
						<label for="notes"><?=__('product.field.notes')?></label>
						<textarea name="notes" class="form-control" rows="2"></textarea>
					</div>
					
					<div class="form-group">
						<label for="cost"><?=__('product.field.cost')?></label>
						<div class="input-group">
							<div class="input-group-addon">€</div>
							<input id="cost" class="form-control" name="cost" type="number" step="0.01" max="500" min="0" value="0" required/>
						</div>
					</div>
					
					<div class="btn-group btn-group-sm pull-right">
							<a class="btn btn-primary" onClick="checkAll()"><?=__('actions.select_all')?></a>
							<a class="btn btn-primary" onClick="uncheckAll()"><?=__('actions.deselect_all')?></a>
						</div>
					<div class="form-group">
						<p><?=__('product.modal.create.participants')?></p>
						
						
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th><?=__('user.field.name')?></th>
										<th><?=__('product.field.amount')?></th>
									</tr>
								</thead>
								<tbody>
								<?php 			
									$active_users = Model_User::get_by_state();
									foreach($active_users as $user):?>
									<tr>
										<td>
											<label class="checkbox-inline">
												<input type="checkbox" class="user-select" name="users[]" value="<?=$user->id?>"> <?=$user->get_fullname()?>
											</label>
										</td>
										<td>
											<input type="number" name="<?=$user->id?>" min="0" max="20" placeholder="1">
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>	
					<br>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="<?=__('product.modal.create.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>
function checkAll() {
	$(".user-select").prop('checked', true);
}

function uncheckAll() {
	$(".user-select").prop('checked', false);
}

function showAddModal() {
	$("#add-product-modal").modal('show');
}

function showDeleteModal(productId, productName) {
	$("#delete-product-modal").modal('show');
	$("#delete-product-name").html(productName);
	$("#delete-product-id").val(productId);
}
</script>
