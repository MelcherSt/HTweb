<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" onClick="showAddModal()" href="#"><i class="fa fa-cart-plus" aria-hidden="true"></i> <?=__('product.index.btn.add_product')?></a>	
				<?php if(\Auth::has_access('products.administration')) { ?>
				<a href="/products/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> <?=__('privileges.perm.manage')?></a>
				<?php } ?>
			</div>
		</div>	
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<p><?=__('product.index.msg')?> <a href="/receipts"><?=__('receipt.title')?></a>.</p>


		<h4><?=__('product.index.paid_by_me')?></h4>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
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
			<table class="table table-striped table-hover">
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

<?=View::forge('modals')->render()?>