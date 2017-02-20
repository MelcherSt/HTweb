<?php
$active_users = Model_User::get_by_state(); 
$active_options = [];
	foreach($active_users as $user) {
		$active_options[$user->id] = $user->get_fullname();
	}
?>


<p>Create a product.</p>

<?=Form::open('/products/create')?>
	<div class="form-group">
		<?=Form::label(__('product.field.name'), 'name')?>
		<?=Form::input('name', '', ['class' => 'form-control', 'type' => 'text', 'required'])?>
	</div>

	<div class="form-group">
		<?=Form::label(__('product.field.notes'), 'notes')?>
		<?=Form::textarea('notes', '', ['class' => 'form-control'])?>
	</div>

	<div class="form-group form-group-sm">
		<?=Form::label(__('product.field.paid_by'), 'payer-id')?>
		<?=Form::select('payer-id', $current_user->id, $active_options, ['class' => 'form-control'])?>	
	</div>	

	<div class="form-group">
		<?=Form::label(__('product.field.cost'), 'cost')?>
		<div class="input-group">
			<div class="input-group-addon">€</div>
			<?=Form::input('cost', null, ['class' => 'form-control', 'type' => 'number', 'max' => 1000, 'min' => 0, 'step' => '0.01'])?>
		</div>
	</div>

	<div class="btn-group btn-group-sm pull-right">
		<a class="btn btn-primary" onClick="checkAll()"><?=__('actions.select_all')?></a>
		<a class="btn btn-primary" onClick="uncheckAll()"><?=__('actions.deselect_all')?></a>
	</div>
	<div class="form-group">
		<p><?=__('product.modal.create.participants')?></p>						
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class="col-md-4"><?=__('user.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.amount')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($active_users as $user) {?>
					<tr>
						<td>
							<label class="checkbox-inline">
								<?=Form::checkbox('users[]', $user->id, ['class' => 'user-select'])?>
								<?=$user->get_fullname()?>
							</label>
						</td>
						<td>
							<?=Form::input($user->id, null, ['type' => 'number', 'min' => 0, 'max' => 20, 'placeholder' => 1])?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<p>In-active users</p>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class="col-md-4"><?=__('user.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.amount')?></th>
					</tr>
				</thead>
				<tbody>
						<?php $inactive_users = Model_User::get_by_state(false);
						foreach($inactive_users as $user) { ?>
						<tr>
							<td>
								<label class="checkbox-inline">
									<?=Form::checkbox('users[]', $user->id)?>
									<?=$user->get_fullname()?>
								</label>
							</td>
							<td>
								<?=Form::input($user->id, null, ['type' => 'number', 'min' => 0, 'max' => 20, 'placeholder' => 1])?>
							</td>
						</tr>
						<?php } ?>
				</tbody>
			</table>
		</div>
	</div>	

	<input type="submit" class="btn btn-primary" value="<?=__('product.modal.create.btn')?>" />			
<?=Form::close()?>


