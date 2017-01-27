<?php 

$deadline = date('H:i', strtotime($session->deadline)); 
$enrollments = $session->get_enrollments(); 
$unenrolled_users = $session->get_unenrolled();
$context = \Sessions\Auth_Context_Session::forge($session, $current_user);
?>


<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/sessions/view/<?=$session->date?>"><i class="fa fa-eye" aria-hidden="true"></i> View original</a>
				<a class="list-group-item" href="#" onClick="showEnrollAddModal()"><i class="fa fa-user-plus" aria-hidden="true"></i> <?=__('session.view.btn.add_enroll')?></a>
			</div>
		</div>
		
		<?php if($session->settled) {?>
		<div class="panel panel-default">
			<div class="panel-heading">Properties</div>
			<div class="panel-body">
				<p>You cannot edit this session since it has been settled.</p>	
				<div class="well">
					<?=$session->notes?>
				</div>
				<dl>
					<dt><?=__('session.field.deadline')?></dt>
					<dd><?=$deadline?></dd>
					<dt><?=__('product.field.cost')?></dt>
					<dd>€ <?=$session->cost?></dd>
					<dt><?=__('product.field.paid_by')?></dt>
					<dd><?=$session->get_payer()->get_fullname() ?></dd>
				</dl>
			</div>
		</div>
		<?php } else { ?>	
		<div class="panel panel-default">
			<div class="panel-heading">Properties</div>
			
			<div class="panel-body">
				<form id="update-session-form" action="/sessions/admin/index/<?=$session->id?>">
					
					
					
					<div class="form-group ">
						<textarea name="notes" class="form-control" rows="2" placeholder="<?=__('session.field.notes')?>"><?=$session->notes?></textarea>
					</div>
					
					<div class="form-group">
						<label for="deadline"><?=__('session.field.deadline')?></label>
						<input class="timepicker form-control" name="deadline" type="text" id="deadline" maxlength="5" size="5" value="<?=$deadline?>"required/>
					</div>

					<div class="form-group">
						<label for="cost"><?=__('product.field.cost')?></label>
						<div class="input-group">
							<div class="input-group-addon">€</div>
							<input style="z-index: 0;" name="cost" class="form-control" type="number" step="0.01" max="100" min="0" value="<?=$session->cost?>"required/>	
						</div>
						<br>
						<label><?=__('product.field.paid_by')?></label>
						<select class="form-control" id="add-user-id" name="payer_id">
							<option value="<?=$session->paid_by?>"><?=$session->get_payer()->get_fullname()?></option>

							<?php foreach($session->enrollments as $enrollment):
									$user_id = $enrollment->user->id;
									if($user_id == $session->paid_by) { continue; }
							?>
							<option value="<?=$user_id?>"><?=$enrollment->user->get_fullname()?></option>
							<?php endforeach;  ?>
						</select>	
					</div>

					<button class="btn btn-sm btn-primary" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update_session')?></button>

				</form>
			</div>
		</div>
		<?php } ?>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?=__('user.field.name')?></th>
						<th>∆ <?=__('session.field.point_plural')?></th>
						<th><?=__('session.field.guest_plural')?></th>	
						<th><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
				<?php 			
					foreach($enrollments as $enrollment): ?>
					<tr>
						<td><?=$enrollment->user->get_fullname()?> 
						<?php 
						if($enrollment->later) { ?>
							*
						<?php }
						
						if ($enrollment->cook) { ?>
							<span class="fa fa-cutlery"></span> 
						<?php } 
						
						if ($enrollment->dishwasher) { ?>
							<span class="fa fa-shower"></span> 
						<?php } ?>

						</td>
						<td><?=$enrollment->get_point_prediction()?>  </td>
						<td><?=$enrollment->guests?></td>
						<td>			
							<a href="#" onclick="showEnrollEditModal(
										<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>', 
										<?=$enrollment->guests?>, <?=$enrollment->cook?>, 
										<?=$enrollment->dishwasher?>,
										<?=(int)$context->has_access(['enroll.other[' . ($enrollment->cook ? 'set-cook,' : '') . 'cook]'])?>, 
										<?=(int)$context->has_access(['enroll.other[' . ($enrollment->dishwasher ? 'set-dishwasher,' : '') . 'dishwasher]'])?>
									)"><span class="fa fa-pencil"></span> <?=__('actions.edit')?></a>  
							| <a href="#" onclick="showEnrollDeleteModal(
										<?=$enrollment->user->id?>,
										'<?=$enrollment->user->name?>'
									)"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>

						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
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
						<label for="edit-guests">Guests </label>
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
			<form id="remove-package" action="/sessions/admin/enroll/<?=$session->date?>" method="POST">
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
							foreach($unenrolled_users as $user):?>
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
		
$('document').ready(function() {
	$('#update-session-form').submit(function(event) {
		event.preventDefault();
		var form = $('#update-session-form');
		
		$.ajax({
			type: 'PUT',
			data: form.serialize(),
			success: function() { 
				alertSuccess(LANG.session.alert.success.update);
			},
			error: function(){ 
				alertError(LANG.session.alert.error.update);
			},
			url: form.attr('action'),
			cache:false
		  });
		  $("#delete-session-modal").modal('hide');
	});
});	
	
function showEnrollAddModal(canCook, canDish) {
	$("#add-enrollment-modal").modal();
	$("#add-cook").attr('disabled', canCook === 0);
	$("#add-dishwasher").attr('disabled', canDish === 0);
}

function showEnrollDeleteModal(userId, userName) {
	$("#delete-enrollment-modal").modal();
	$("#delete-user-name").html(userName);
	$("#delete-user-id").val(userId);
}

function showEnrollEditModal(userId, userName, guests, cook, dishwasher, canCook, canDish) {
	$("#edit-enrollment-modal").modal();
	$("#edit-user-name").html(userName);
	$("#edit-user-id").val(userId);
	$("#edit-guests").val(guests);
	$("#edit-cook").prop('checked', cook === 1);
	$("#edit-dishwasher").prop('checked', dishwasher === 1);
	$("#edit-cook").attr('disabled', canCook === 0);
	$("#edit-dishwasher").attr('disabled', canDish === 0);
	
}
</script>