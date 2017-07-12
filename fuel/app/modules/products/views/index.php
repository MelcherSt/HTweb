<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/products/create"><i class="fa fa-plus" aria-hidden="true"></i> <?=__('product.index.btn.add_product')?></a>	
				<?php if(\Auth::has_access('products.administration')) { ?>
				<a href="/products/admin" class="list-group-item"><i class="fa fa-list" aria-hidden="true"></i> <?=__('privileges.perm.manage')?></a>
				<?php } ?>
			</div>
		</div>	
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<p><?=__('product.index.msg')?> <a href="/receipts"><?=__('receipt.title')?></a>.</p>
		<?=Presenter::forge('overview')?>
	</div>
</div>

<?=View::forge('modals')?>