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
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('product.field.date')?></th>	
						<th class="col-md-2"><?=__('product.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.paid_by')?></th>
						<th class="col-md-2"><?=__('product.field.participant_plural')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($products as $product){ ?>
					<tr class="clickable-row <?=$product->is_paid_by() ? 'info' : ''?>" data-href="/products/view/<?=$product->id?>">
						<td><?=strftime('%d/%m/%Y (%A)', strtotime($product->date))?></td>
						<td><?=$product->name?></td>
						<td><?=$product->get_payer()->get_shortname()?>
						<td><?=$product->get_nicified_participants()?></td>
						<td><?='€ ' . $product->cost?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
		</div>
	</div>
</div>

<?=View::forge('modals')->render()?>