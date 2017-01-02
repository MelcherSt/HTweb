<button type="button" class="btn btn-primary" onClick="showAddProduct()">
	<span class="fa fa-cart-plus"></span>
	<?=__('product.index.btn.add_product')?>
</button>


<!-- Modal dialog for product creation -->
<div id="add-product-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/products/create/" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.create.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.create.msg')?></p>					
					
					<div class="form-group">
						<label for="name"><?=__('product.field.name')?></label>
						<input name="name" class="form-control"></input>
					</div>
					
					<div class="form-group">
						<label for="notes"><?=__('product.field.notes')?></label>
						<textarea name="notes" class="form-control" rows="2"></textarea>
					</div>
					
					<div class="form-group">
						<label for="cost"><?=__('product.field.cost')?></label>
						<input id="add-guests" class="form-control" name="cost" type="number" step="0.01" max="500" min="0" value="0"/>
					</div>
					
					
					<div class="btn-group btn-group-sm pull-right">
							<a class="btn btn-primary" onClick="checkAll()"><?=__('actions.select_all')?></a>
							<a class="btn btn-primary" onClick="uncheckAll()"><?=__('actions.deselect_all')?></a>
						</div>
					<div class="form-group">
						<p><?=__('product.modal.create.participants')?></p>
						
						
						<div class="table-responsive">
							<table class="table table-hover">
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

function showAddProduct() {
	$("#add-product-modal").modal('show');
}
</script>
