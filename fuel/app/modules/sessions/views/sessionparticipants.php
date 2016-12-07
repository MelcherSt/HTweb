<?php

$enrollments = $session->enrollments; 
$cur_enrollment = $session->current_enrollment();
?>
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Guests</th>
				<?php if (isset($cur_enrollment) && $cur_enrollment->cook): ?>
				<th>Actions</th>
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
				<td><?=$enrollment->guests?></td>
				<?php if (isset($cur_enrollment) && $cur_enrollment->cook): ?>
				<td>
									
					<a href="#" onclick="showEditModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>', '<?=$enrollment->guests?>', '<?=$enrollment->cook?>', '<?=$enrollment->dishwasher?>')"><span class="fa fa-pencil"></span> Edit</a>  
					<?php if ($cur_enrollment->user_id != $enrollment->user_id): ?> |
					<a href="#" onclick="showDeleteModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>')"><span class="fa fa-close"></span> Remove</a>
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
		<button type="button" class="btn btn-primary pull-right" onClick="showAddModel()"><span class="fa fa-plus"></span> Enroll new user</button>
		<?php endif; ?>

	<p class="pull-left">There are <?=$session->count_total_participants()?> participants in total
		of which <?=$session->count_guests()?> are guests.</p>
</div>

<!-- Modal dialog for enrollment deletion -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/sessions/enrollments/delete/<?=$session->date?>" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete enrollment</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete <strong><span id="delete-user-name"></span></strong> from this session?</p>
					<!--  insert form elements here -->
					<div class="form-group">
						<input id="delete-user-id" type="hidden" class="form-control" name="user_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="Delete user from session" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Cancel</button>
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
					<h4 class="modal-title">Edit enrollment</h4>
				</div>
				<div class="modal-body">
					<p>You are editing the enrollment of <strong><span id="edit-user-name"></span></strong></p>
					<!--  insert form elements here -->
					<div class="form-group">
						<input id="edit-user-id" type="hidden" class="form-control" name="user_id">
						<label for="edit-guests">Guests</label>
						<input id="edit-guests" name="guests" type="number" step="1" max="10" min="0" value=""/>
					</div>
					
					<div class="form-group">
						<label>Roles</label>
						<div class="checkbox">
							<label><input id="edit-cook" name="cook" type="checkbox">Cook</label>
						</div>
						<div class="checkbox">
							<label><input id="edit-dishwasher" name="dishwasher" type="checkbox">Dishwasher</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="Update enrollment" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Cancel</button>
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
					<h4 class="modal-title">Create enrollment</h4>
				</div>
				<div class="modal-body">
					<p>Enroll a new user into this session.</p>
					<!--  insert form elements here -->
					
					<div class="form-group">
						<label for="add-user-id">Select a user</label>
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
						<label for="add-guests">Guests</label>
						<input id="add-guests" name="guests" type="number" step="1" max="10" min="0" value="0"/>
					</div>
					
					<div class="form-group">
						<label>Roles</label>
						<div class="checkbox">
							<label><input id="add-cook" name="cook" type="checkbox">Cook</label>
						</div>
						<div class="checkbox">
							<label><input id="add-dishwasher" name="dishwasher" type="checkbox">Dishwasher</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="Create enrollment" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>

function showAddModel() {
	$("#add-enrollment-modal").modal('show');
}

function showDeleteModal(userId, userName) {
	$("#delete-enrollment-modal").modal('show');
	$("#delete-user-name").html(userName);
	$("#delete-user-id").val(userId);
}

function showEditModal(userId, userName, guests, cook, dishwasher) {
	$("#edit-enrollment-modal").modal('show');
	$("#edit-user-name").html(userName);
	$("#edit-user-id").val(userId);
	$("#edit-guests").val(guests);
	$("#edit-cook").prop('checked', cook == 1);
	$("#edit-dishwasher").prop('checked', dishwasher == 1);
}

</script>