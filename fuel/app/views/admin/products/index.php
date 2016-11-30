<h2>Listing Products</h2>
<br>
<?php if ($products): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>Notes</th>
			<th>Price</th>
			<th>Paid by</th>
			<th>Settled</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($products as $item): ?>		<tr>

			<td><?php echo $item->title; ?></td>
			<td><?php echo $item->notes; ?></td>
			<td><?php echo $item->price; ?></td>
			<td><?php echo $item->paid_by; ?></td>
			<td><?php echo $item->settled; ?></td>
			<td>
				<?php echo Html::anchor('admin/products/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/products/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/products/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Products.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/products/create', 'Add new Product', array('class' => 'btn btn-success')); ?>

</p>
