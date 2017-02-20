<?php


?>

<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/users/admin/create" ><span class="fa fa-plus"></span> <?=__('user.create.btn')?></a>
			</div>
		</div>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th><?=__('user.field.username')?></th>
						<th><?=__('user.field.name')?></th>
						<th><?=__('user.field.surname')?></th>
						<th><?=__('user.field.active')?></th>
						<th><?=__('user.field.group')?></th>
						<th><?=__('user.field.email')?></th>
						<th class="col-md-2"><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user): ?>		
					<tr class="clickable-row" data-href="/users/view/<?=$user->id?>">
						<td><?=$user->username?></td>
						<td><?=$user->name?></td>
						<td><?=$user->surname?></td>
						<td><?=$user->active?></td>
						<td><?=Auth::group()->get_name($user->group_id)?></td>
						<td><?=$user->email?></td>
						<td>
							<?=Html::anchor('users/admin/edit/'.$user->id, 'Edit')?> |
							<?=Html::anchor('users/admin/delete/'.$user->id, 'Delete', array('onclick' => "return confirm('Are you sure?')"))?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<em><?=sizeof($users) == 0 ? __('user.empty_list') : ''?></em>
		</div>
	</div>	
</div>