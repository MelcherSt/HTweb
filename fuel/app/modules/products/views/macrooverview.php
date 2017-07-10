<div class="table-responsive">
	<table class="table table-striped table-hover">
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


<!-- Modal dialog for macro deletion -->
<div id="delete-macro-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/products/admin/macro/delete')?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.remove_macro.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.remove_macro.msg')?> <strong><span id="delete-macro-name"></span></strong>?</p>
					<?=Form::hidden('macro-id', null, ['id' => 'delete-product-id'])?>
				</div>
				<div class="modal-footer">					
					<?=Form::submit(['value'=> __('product.modal.remove_macro.btn'), 'name'=>'submit', 'class' => 'btn btn-danger'])?>	
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>