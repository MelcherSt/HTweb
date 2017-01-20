

<div class="well">
	<?=$product->notes?>
</div>

<dl>
	<dt><?=__('product.field.cost')?></dt>
	<dd>€ <?=$product->cost?></dd>
	<dt><?=__('product.field.paid_by')?></dt>
	<dd><?=$product->get_payer()->get_fullname() ?></dd>
</dl>

