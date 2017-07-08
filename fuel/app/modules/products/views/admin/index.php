<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/products/admin/create" ><span class="fa fa-plus"></span> <?=__('product.index.btn.add_product')?></a>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		 <div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('product.field.date')?></th>	
						<th class="col-md-2"><?=__('product.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.paid_by')?></th>
						<th class="col-md-2"><?=__('product.field.participant_plural')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
						<th class="col-md-2"><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($products as $product){ ?>
					<tr class="clickable-row" data-href="/products/view/<?=$product->id?>">
						<td><?=strftime('%d/%m/%Y (%A)', strtotime($product->date))?></td>
						<td><?=$product->name?></td>
						<td><?=$product->get_payer()->get_shortname()?>
						<td><?=$product->get_nicified_participants()?></td>
						<td><?='€ ' . $product->cost?></td>
						<td><a href="#" data-href="#" class="clickable-row" onclick="showDeleteModal(<?=$product->id?>, '<?=$product->name?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
		</div>
	</div>
</div>
<?=View::forge('modals')->render()?>