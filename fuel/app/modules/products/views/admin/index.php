<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/products/admin/create" ><span class="fa fa-plus"></span> <?=__('product.index.btn.add_product')?></a>
				<a class="list-group-item" href="/products/admin/gen" ><span class="fa fa-repeat"></span> <?=__('product.index.btn.gen_product')?></a>	
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<?=Presenter::forge('overview', 'admin')?>
	</div>
</div>

<?=View::forge('modals')?>