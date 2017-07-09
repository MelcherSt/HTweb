<!-- Modal dialog for session deletion -->
<div id="delete-product-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/products/delete')?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.remove.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.remove.msg')?> <strong><span id="delete-product-name"></span></strong>?</p>
					<?=Form::hidden('product-id', null, ['id' => 'delete-product-id'])?>
				</div>
				<div class="modal-footer">					
					<?=Form::submit(['value'=> __('product.modal.remove.btn'), 'name'=>'submit', 'class' => 'btn btn-danger'])?>	
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>

<!-- Modal dialog for product creation -->
<div id="add-product-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open(['action' => '/products/create', 'class' => 'form-horizontal'])?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.create.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.create.msg')?></p>					
					
					<div class="form-group">
						<?=Form::label(__('product.field.name').'*', 'name', ['class' => 'col-sm-2'])?>
						<div class="col-sm-10">
							<?=Form::input('name', '', ['class' => 'form-control', 'type' => 'text', 'required'])?>
						</div>
					</div>
								
					<div class="form-group">
						<?=Form::label(__('product.field.notes'), 'notes', ['class' => 'col-sm-2'])?>
						<div class="col-sm-10">
							<?=Form::textarea('notes', '', ['rows' => '1', 'class' => 'form-control'])?>
						</div>
					</div>
					
					<div class="form-group">
						<?=Form::label(__('product.field.date').'*', 'date', ['class' => 'col-sm-2'])?>
						
						<div class="col-sm-10">
							<?=Form::input('date', date('Y-m-d'), ['class' => 'form-control', 'type' => 'date', 'required'])?>
						</div>
					</div>			
					
					<div class="form-group">
						<?=Form::label(__('product.field.cost').'*', 'cost', ['class' => 'col-sm-2'])?>
						
						<div class="col-sm-10">
							<div class="input-group">
								<div class="input-group-addon">€</div>
								<?=Form::input('cost', null, ['class' => 'form-control', 'type' => 'number', 'max' => 1000, 'min' => -1000, 'step' => '0.01', 'required'])?>
							</div>
						</div>
					</div>
					
					<div class="btn-group btn-group-sm pull-right">
						<a class="btn btn-primary" onClick="checkAll()"><?=__('actions.select_all')?></a>
						<a class="btn btn-primary" onClick="uncheckAll()"><?=__('actions.deselect_all')?></a>
					</div>
					<p><?=__('product.modal.create.participants').'*'?></p>
					<div class="form-group">						
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th><?=__('user.field.name')?></th>
										<th><?=__('product.field.amount')?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach(Model_User::get_by_state() as $user) {?>
									<tr>
										<td>
											<label class="checkbox-inline" style="padding-top: 0px !important;">
												<?=Form::checkbox('users[]', $user->id, ['class' => 'user-select'])?>
												<?=$user->get_fullname()?>
											</label>
										</td>
										<td>
											<?=Form::input($user->id, null, ['type' => 'number', 'min' => 0, 'max' => \Sessions\Model_Session::MAX_GUESTS, 'placeholder' => 1])?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>	
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="<?=__('product.modal.create.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>