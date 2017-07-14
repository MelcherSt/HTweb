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
				<td><?=strftime('%d/%m/%Y (%A)', strtotime($product->date))?></td>
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

<!-- Modal dialog for session deletion -->
<div id="delete-product-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/products/delete')?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.remove.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.remove.msg')?> <strong><span id="delete-product-name"></span></strong>?</p>
					<?=Form::hidden('product-id', null, ['id' => 'delete-product-id'])?>
				</div>
				<div class="modal-footer">					
					<?=Form::submit(['value'=> __('product.modal.remove.btn'), 'name'=>'submit', 'class' => 'btn btn-danger'])?>	
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>
