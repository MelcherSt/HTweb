<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th class="col-md-2"><?=__('product.field.name')?></th>
				<th class="col-md-2"><?=__('product.field.paid_by')?></th>
				<th class="col-md-1"><?=__('product.field.cost')?></th>
				<th class="col-md-2"><?=__('product.field.macro_last_executed')?></th>	
				<th class="col-md-2"><?=__('actions.name')?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($products as $product){ ?>
			<tr>						
				<td><?=$product->name?></td>
				<td><?=$product->get_payer()->get_shortname()?>
				<td><?='€ ' . $product->cost?></td>
				<td><?=strftime('%d/%m/%Y (%A)', strtotime($product->last_execution))?></td>
				<td>
					<a href="#" class="clickable-row" data-toggle="modal" data-target="#delete-macro-modal" data-macro-id="<?=$product->id?>" data-macro-name="<?=$product->name?>"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
</div>