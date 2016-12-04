<h2>Listing Users</h2>
<br>
<?php if ($users): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Username</th>
			<th>Name</th>
			<th>Surname</th>
			<th>Phone</th>
			<th>Active</th>
			<th>Start year</th>
			<th>End year</th>
			<th>Point count</th>
			<th>Balance</th>
			<th>Group</th>
			<th>Email</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($users as $item): ?>		<tr>

			<td><?php echo $item->username; ?></td>
			<td><?php echo $item->name; ?></td>
			<td><?php echo $item->surname; ?></td>
			<td><?php echo $item->phone; ?></td>
			<td><?php echo $item->active; ?></td>
			<td><?php echo $item->start_year; ?></td>
			<td><?php echo $item->end_year; ?></td>
			<td><?php echo $item->points; ?></td>
			<td><?php echo $item->balance; ?></td>
			<td><?php echo $item->group_id; ?></td>
			<td><?php echo $item->email; ?></td>
			<td>
				<?php echo Html::anchor('admin/users/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/users/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/users/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Users.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/users/create', 'Add new User', array('class' => 'btn btn-success')); ?>

</p>
