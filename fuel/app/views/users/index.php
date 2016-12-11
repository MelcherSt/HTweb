<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Phone</th>
			<th>Email</th>
		</tr>
	</thead>
		<tbody>
		<?php foreach ($users as $user): ?>		
		<tr class="clickable-row" data-href="/users/view/<?=$user->id?>">
			<td><?=$user->get_fullname()?></td>
			<td><?=$user->phone?></td>
			<td><?=$user->email?></td>
		</tr>
		<?php endforeach; ?>	
	</tbody>
</table>
