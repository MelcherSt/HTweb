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
					<tr id="session-<?=$session->id?>" class="clickable-row" data-href="/sessions/admin/view/<?=$session->date?>">
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
						<td><a href="#" data-href="#" class="clickable-row" onclick="showDeleteModal(<?=$session->id?>, '<?=$session->date?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?=sizeof($sessions) == 0 ? __('session.empty_list') : ''?>
		</div>
	</div>
</div>

<!-- Modal dialog for session deletion -->
<div id="delete-session-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="delete-session-form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.remove.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.remove.msg')?> <strong><span id="delete-session-date"></span></strong>?</p>
					<div class="form-group">
						<input id="delete-session-id" type="hidden" class="form-control" name="session_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="<?=__('session.modal.remove.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>