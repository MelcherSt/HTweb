<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-hover" 
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
					<?php foreach($products as $product): ?>
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
			<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>			
		</div>
	</div>
</div>

<?=View::forge('modals')->render()?>