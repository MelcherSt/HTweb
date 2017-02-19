<?php if ($users): ?>
<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>Username</th>
			<th>Name</th>
			<th>Surname</th>
			<th>Active</th>
			<th>Group</th>
			<th>Email</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($users as $user): ?>		
		<tr class="clickable-row" data-href="/users/view/<?=$user->id?>">
			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->name; ?></td>
			<td><?php echo $user->surname; ?></td>
			<td><?php echo $user->active; ?></td>
			<td><?php echo $user->group_id; ?></td>
			<td><?php echo $user->email; ?></td>
			<td>
				<?=Html::anchor('users/admin/edit/'.$user->id, 'Edit')?> |
				<?=Html::anchor('users/admin/delete/'.$user->id, 'Delete', array('onclick' => "return confirm('Are you sure?')"))?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<em>No Users.</em>

<?php endif; ?><p>
	<?php echo Html::anchor('users/admin/create', 'Add new User', array('class' => 'btn btn-success')); ?>

</p>
