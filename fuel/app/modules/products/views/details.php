

<div class="well">
	<?=$product->notes?>
</div>

<p><?=__('product.field.cost')?>: € <?=$product->cost?>
	<br>
	<?=__('product.field.paid_by')?>: <?=$product->payer->get_fullname() ?>


</p>
