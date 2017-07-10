<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/products/admin/gen/create" ><span class="fa fa-plus"></span> <?=__('product.gen.btn.add_definition')?></a>
				<a class="list-group-item" href="/products/admin/gen/execute" ><span class="fa fa-play"></span> <?=__('product.gen.btn.execute_all')?></a>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('product.field.name')?></th>
						<th class="col-md-2"><?=__('product.field.paid_by')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
						<th class="col-md-2"><Last execution</th>	
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
							<a href="#" class="clickable-row" data-toggle="modal" data-target="#delete-product-gen-modal" data-product-id="<?=$product->id?>" data-product-name="<?=$product->name?>"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
		</div>
	</div>
</div>

<?=View::forge('admin/gen/modals')?>

<script>
$('#delete-product-gen-modal').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var productId = $(e.relatedTarget).data('product-id');
	var productName = $(e.relatedTarget).data('product-name');

    //populate the textbox
    $('#delete-product-id').val(productId);
	$('#delete-product-name').html(productName);
});	
</script>