<?php

$enrollments = $session->get_enrollments_sorted(); 
$cur_enrollment = $session->current_enrollment();

$can_cook = (int)$session->can_enroll_cooks(true);
$can_dish = (int)$session->can_enroll_dishwashers(true);


?>
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?=__('user.field.name')?></th>
				<th>∆ <?=__('session.field.point_plural')?></th>
				<th><?=__('session.field.cost')?></th>	
				<?php if (isset($cur_enrollment) && $cur_enrollment->cook): ?>
				<th><?=__('actions.name')?></th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php 			
			foreach($enrollments as $enrollment): ?>
			<tr>
				<td><?=$enrollment->user->get_fullname()?> 
				<?php if ($enrollment->cook): ?>
					<span class="fa fa-cutlery"></span> 
				<?php endif; ?>
				<?php if ($enrollment->dishwasher): ?>
					<span class="fa fa-shower"></span> 
				<?php endif; ?>
				</td>
				<td><?=$enrollment->get_point_prediction()?>  </td>
				<td><?=$enrollment->guests?></td>
				<?php if (isset($cur_enrollment) && $cur_enrollment->cook): ?>
				<td>			
					<a href="#" onclick="showEditModal(
								<?=$enrollment->user->id?>, 
								'<?=$enrollment->user->name?>', 
								'<?=$enrollment->guests?>', 
								'<?=$enrollment->cook?>', 
								'<?=$enrollment->dishwasher?>',
								<?=$can_cook?>, 
								<?=$can_dish?>
							)"><span class="fa fa-pencil"></span> <?=__('actions.edit')?></a>  
					<?php if ($cur_enrollment->user_id != $enrollment->user_id): ?> |
					<a href="#" onclick="showDeleteModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<div class="row">
	<?php if (isset($cur_enrollment) && $cur_enrollment->cook && $session->can_change_enrollments()): ?>
		<button type="button" class="btn btn-primary pull-right" onClick="showAddModel(
					<?=$can_cook?>, 
					<?=$can_dish?>
				)"><span class="fa fa-user-plus"></span>
		 <?=__('session.view.btn.add_enroll')?>
		</button>
		<?php endif; ?>

	<p class="pull-left">
	<?=__('session.view.msg', (array('p_count' => $session->count_total_participants(), 'g_count' => $session->count_guests()))) ?>
	</p>
</div>

<!-- Modal dialog for enrollment deletion -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/sessions/enrollments/delete/<?=$session->date?>" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.remove_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.remove_enroll.msg')?> <strong><span id="delete-user-name"></span></strong>?</p>
					<div class="form-group">
						<input id="delete-user-id" type="hidden" class="form-control" name="user_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="<?=__('session.modal.remove_enroll.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal dialog for enrollment editing -->
<div id="edit-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/sessions/enrollments/update/<?=$session->date?>" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.edit_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.edit_enroll.msg')?> <strong><span id="edit-user-name"></span></strong>.</p>
					<div class="form-group">
						<input id="edit-user-id" type="hidden" class="form-control" name="user_id">
						<label for="edit-guests"><?=__('session.field.guest_plural')?></label>
						<input id="edit-guests" name="guests" type="number" step="1" max="10" min="0" value=""/>
					</div>
					
					<div class="form-group">
						<label><?=__('session.role.name_plural')?></label>
						<div class="checkbox">
							<label><input id="edit-cook" name="cook" type="checkbox"><?=__('session.role.cook')?></label>
						</div>
						<div class="checkbox">
							<label><input id="edit-dishwasher" name="dishwasher" type="checkbox"><?=__('session.role.dishwasher')?></label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="<?=__('session.modal.edit_enroll.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal dialog for enrollment creation -->
<div id="add-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/sessions/enrollments/create/<?=$session->date?>" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.create_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.create_enroll.msg')?></p>					
					<div class="form-group">
						<label for="add-user-id"><?=__('user.name')?>:</label>
						<select class="form-control" id="add-user-id" name="user_id">
							<?php 
							$active_users = Model_User::get_by_state();
							foreach($active_users as $user):?>
							<option value="<?=$user->id?>"><?=$user->get_fullname()?></option>
							<?php endforeach; ?>
						</select>
					</div>	
					<br>
					<div class="form-group">
						<label for="add-guests"><?=__('session.field.guest_plural')?></label>
						<input id="add-guests" name="guests" type="number" step="1" max="10" min="0" value="0"/>
					</div>
					
					<div class="form-group">
						<label><?=__('session.role.name_plural')?></label>
						<div class="checkbox">
							<label><input id="add-cook" name="cook" type="checkbox"><?=__('session.role.cook')?></label>
						</div>
						<div class="checkbox">
							<label><input id="add-dishwasher" name="dishwasher" type="checkbox"><?=__('session.role.dishwasher')?></label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="<?=__('session.modal.create_enroll.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>


<!-- //TODO: externalize -->
<script>
function showAddModel(canCook, canDish) {
	$("#add-enrollment-modal").modal('show');
	$("#add-cook").attr('disabled', canCook === 0);
	$("#add-dishwasher").attr('disabled', canDish === 0);
}

function showDeleteModal(userId, userName) {
	$("#delete-enrollment-modal").modal('show');
	$("#delete-user-name").html(userName);
	$("#delete-user-id").val(userId);
}

function showEditModal(userId, userName, guests, cook, dishwasher, canCook, canDish) {
	$("#edit-enrollment-modal").modal('show');
	$("#edit-user-name").html(userName);
	$("#edit-user-id").val(userId);
	$("#edit-guests").val(guests);
	$("#edit-cook").prop('checked', cook === 1);
	$("#edit-dishwasher").prop('checked', dishwasher === 1);
	$("#edit-cook").attr('disabled', canCook === 0);
	$("#edit-dishwasher").attr('disabled', canDish === 0);
	
}
</script>