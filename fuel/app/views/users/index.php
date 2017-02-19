<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
		
		<?=Request::forge('/privileges/users/nav')->execute();?>
		
	</div>

	<!-- BODY -->
	<div class="col-md-8">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th><?=__('user.field.name')?></th>
					<th><?=__('user.field.phone')?></th>
					<th><?=__('user.field.email')?></th>
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
	</div>
</div>
	



