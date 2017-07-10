<?php
$participants = $product->get_participants_sorted();
$context = Products\Context_Products::forge($product);
?>

<div class="row">	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('product.name')?></div>			
			<div class="panel-body">
				<?php if($context->update()) { ?>
				<!-- Editable session properties -->
				<?=Form::open('/products/update/'. $product->id)?>	
				
				<div class="form-group form-group-sm">
					<?=Form::label(__('product.field.notes'), 'notes')?>
					<?=Form::textarea('notes', $product->notes, ['class' => 'form-control'])?>
				</div>	

				<div class="form-group form-group-sm">
					<?=Form::label(__('product.field.cost'), 'cost')?>
					<div class="input-group">
						<div class="input-group-addon">€</div>
						<?=Form::input('cost', $product->cost, ['class' => 'form-control', 'type' => 'number', 'min' => Products\Model_Product::MIN_PRICE,'max' => Products\Model_Product::MAX_PRICE, 'step' => '0.01', 'style' => 'z-index: 0;'])?>
					</div>
				</div>	
				
				<?=Form::submit(['value'=> __('product.view.btn.update_product'), 'name'=>'submit', 'class' => 'btn btn-sm btn-primary btn-block'])?>
				<?=Form::close()?>
				<?php } else { ?>
				<!-- Static product properties -->
				<div class="well">
					<?=$product->notes?>
				</div>
				<dl>
					<dt><?=__('product.field.cost')?></dt>
					<dd>€ <?=$product->cost?></dd>
					<dt><?=__('product.field.paid_by')?></dt>
					<dd><?=$product->get_payer()->get_fullname() ?></dd>
				</dl>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<h3><?=__('product.field.participant_plural')?></h3>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th><?=__('user.field.name')?></th>
						<th><?=__('product.field.amount')?></th>
						<th><?=__('receipt.field.balance')?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($participants as $participant): ?>
					<tr>
						<td><?=$participant->user->get_fullname()?></td>
						<td><?=$participant->amount?></td>
						<td>€ <?=round(-1 * ($product->cost / $product->count_total_participants()) * $participant->amount, 2)?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>	
