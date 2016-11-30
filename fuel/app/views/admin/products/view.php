<h2>Viewing #<?php echo $product->id; ?></h2>

<p>
	<strong>Title:</strong>
	<?php echo $product->title; ?></p>
<p>
	<strong>Notes:</strong>
	<?php echo $product->notes; ?></p>
<p>
	<strong>Price:</strong>
	<?php echo $product->price; ?></p>
<p>
	<strong>Paid by:</strong>
	<?php echo $product->paid_by; ?></p>
<p>
	<strong>Settled:</strong>
	<?php echo $product->settled; ?></p>

<?php echo Html::anchor('admin/products/edit/'.$product->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/products', 'Back'); ?>