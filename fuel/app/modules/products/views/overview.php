<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed">
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
			<tr class="clickable-row <?=$product->is_paid_by() && !isset($hide_colors) ? 'info' : ''?>" data-href="/products/view/<?=$product->id?>">
				<td><?=strftime('%d/%m (%A)', strtotime($product->date))?></td>
				<td><?=$product->generated ? '<i class="fa fa-repeat" title="'.__('product.index.tooltip_macro').'"></i>' : ''?> <?=$product->name?></td>
				<td><?=$product->get_payer()->get_shortname()?>
				<td><?=$product->get_nicified_participants()?></td>
				<td><?='€ ' . $product->cost?></td>
				<td>
					<?php if($product->is_paid_by() && !$product->generated || isset($is_admin)) { ?>
					<a href="#" data-href="#" class="clickable-row" data-toggle="modal" data-target="#delete-product-modal" data-product-id="<?=$product->id?>" data-product-name="<?=$product->name?>"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
</div>

<?=View::forge('modals')?>