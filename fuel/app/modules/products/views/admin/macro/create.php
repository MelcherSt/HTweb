<div class="container">
	<p><?=__('product.admin.create_macro.msg')?></p>
	<?=Form::open(['action' => '/products/admin/macro/create', 'class' => 'form-horizontal'])?>
	<div class="form-group">
		<?=Form::label(__('product.field.name').'*', 'name', ['class' => 'col-sm-2'])?>
		<div class="col-sm-6">
			<?=Form::input('name', '', ['class' => 'form-control', 'type' => 'text', 'maxlength' => 50, 'required'])?>
		</div>
	</div>	

	<div class="form-group">
		<?=Form::label(__('product.field.cost').'*', 'cost', ['class' => 'col-sm-2'])?>
		<div class="col-sm-3">
			<div class="input-group">
				<div class="input-group-addon">€</div>
				<?=Form::input('cost', null, ['class' => 'form-control', 'type' => 'number', 'min' => Products\Model_Product::MIN_PRICE,'max' => Products\Model_Product::MAX_PRICE, 'step' => '0.01', 'required'])?>
			</div>
		</div>
	</div>

	<div class="form-group">
		<?=Form::label(__('product.field.paid_by').'*', 'payer-id', ['class' => 'col-sm-2'])?>
		<div class="col-sm-3">
			<?=Form::select('payer-id', $current_user->id, $active_user_options, ['class' => 'form-control', 'required'])?>	
		</div>
	</div>

	<input type="submit" class="btn btn-primary btn-block" value="<?=__('product.admin.create_macro.btn')?>" />			
	<?=Form::close()?>
</div>

