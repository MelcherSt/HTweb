<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="#"><i class="fa fa-trash" aria-hidden="true"></i> Some useful actions here...</a>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('session.field.date')?></th>
						<th class="col-md-1"><?=__('session.role.participant_plural')?></th>
						<th class="col-md-2"><?=__('session.role.cook_plural')?></th>
						<th class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
						<th class="col-md-2"><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($sessions as $session): ?>
					<tr class="clickable-row" data-href="/sessions/admin/view/<?=$session->date?>">
						<td><?=strftime('%A (%e/%m)', strtotime($session->date))?></td>
						<td><?=$session->count_total_participants()?></td>
						<td>
							<?php foreach($session->get_cook_enrollments() as $enrollment):?>
								<?=$enrollment->user->name;?>			
							<?php endforeach; ?>	
						</td>
						<td>
							<?php foreach($session->get_dishwasher_enrollments() as $enrollment):?>
								<?=$enrollment->user->name;?> 	
							<?php endforeach; ?>
						</td>
						<td><?='€ ' . $session->cost?></td>
						<td><a href="#" data-href="#" class="clickable-row" onclick="showDeleteModal(<?=$session->id?>)"><span class="fa fa-close"></span> <?=__('actions.remove')?></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?=sizeof($sessions) == 0 ? __('session.empty_list') : ''?>
		</div>
	</div>
</div>