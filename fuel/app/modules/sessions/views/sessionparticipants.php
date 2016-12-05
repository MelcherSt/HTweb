<?php

$enrollments = $session->enrollments; 
$cur_enrollment = $session->current_enrollment();
?>

<!-- Modal dialog for package removal -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="#" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title">Remove package</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete <span id="user-name"></span> from this session??</p>
					<!--  insert form elements here -->
					<div class="form-group">
						<input id="user-id" type="hidden" class="form-control" name="user-id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="Remove" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

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
				<td><?=$enrollment->user->get_fullname()?></td>
				<td><?=$enrollment->guests?></td>
				<?php if (isset($cur_enrollment) && $cur_enrollment->cook): ?>
				<td>
					<?php if ($cur_enrollment->user_id != $enrollment->user_id): ?>				
					<a href="#"><span class="fa fa-pencil"></span> Edit</a> | <a href="#" onclick="showDeleteModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>')"><span class="fa fa-close"></span> Remove</a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<p class="pull-right">Total participants <?=$session->count_total_participants()?> 
	of which <?=$session->count_guests()?> are guests.</p>




<script>

function showDeleteModal(userId, userName) {
	$("#delete-enrollment-modal").modal('show');
	$("#user-name").html(userName);
	$("#user-id").val(userId);
}	

</script>

<style>
.modal-open[style] {
padding-right: 0px !important;
}
</style>