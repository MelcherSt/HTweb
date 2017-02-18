<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-1">Id</th>
						<th class="col-md-1"><?=__('privileges.field.area')?></th>
						<th class="col-md-1"><?=__('privileges.field.permission')?></th>
						<th class="col-md-2"><?=__('actions.name')?></th>
						<th class="col-md-3"><?=__('privileges.field.description')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($permissions as $permission): ?>
					<tr id="privilege-<?=$permission->id?>">
						<td><?=$permission->id?></td>
						<td><?=$permission->area?></td>
						<td><?=$permission->permission?></td>
						<td><?= implode(' | ', $permission->actions)?></td>
						<td><?=__($permission->description)?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<em><?=sizeof($permissions) == 0 ? __('privileges.empty_list') : ''?></em>
		</div>
	</div>
</div>