<!-- Modal dialog for product deletion -->
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


<!-- Modal dialog for product macro deletion -->
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
					<?=Form::hidden('macro-id', null, ['id' => 'delete-macro-id'])?>
				</div>
				<div class="modal-footer">					
					<?=Form::submit(['value'=> __('product.modal.remove_macro.btn'), 'name'=>'submit', 'class' => 'btn btn-danger'])?>	
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>