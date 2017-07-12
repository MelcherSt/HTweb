<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/products/admin/create" ><span class="fa fa-plus"></span> <?=__('product.admin.index.btn.add_product').' ('.__('actions.advanced').')'?></a>
				<a class="list-group-item" href="/products/admin/macro/create" ><span class="fa fa-repeat"></span> <?=__('product.admin.index.btn.add_macro')?></a>
				<a class="list-group-item" href="/products/admin/macro/execute" ><span class="fa fa-play"></span> <?=__('product.admin.index.btn.execute_macros')?></a>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<h3><?=__('product.admin.table_macros')?></h3>	
		<?=Presenter::forge('macrooverview')?>
	
		<h3><?=__('product.admin.table_products')?></h3>
		<?=Presenter::forge('overview', 'admin')?>
	</div>
</div>

<?=View::forge('modals')?>