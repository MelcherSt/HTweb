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
				<a class="list-group-item" href="/sessions/<?=$session->date?>"><i class="fa fa-eye" aria-hidden="true"></i> <?=__('session.admin.view.original')?></a>
				<?php if(! $session->settled) {?><a class="list-group-item" href="#" onClick="showEnrollAddModal(<?=$session->id?>)"><i class="fa fa-user-plus" aria-hidden="true"></i> <?=__('session.view.btn.add_enroll')?></a> <?php } ?>
			</div>
		</div>
		
		
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.properties')?></div>
			<div class="panel-body">
			<?php if($session->settled) {?>
				<p><?=__('session.admin.view.settled')?></p>	
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
			<?php } else { ?>	
		
			<form id="update-session-form" 
				  action="/api/v1/sessions/<?=$session->id?>" 
				  method="put"
				  data-alert-success="<?=__('session.alert.success.update_session')?>"
				  data-alert-error="<?=__('session.alert.error.update_session')?>"
			>
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
			<?php } ?>
			</div>	
		</div>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<div class="table-responsive">
			<table
				id="enrollments-table"
				data-toggle="table"
				data-url="/api/v1/sessions/<?=$session->id?>/enrollments"
				data-sort-name="user.name"
				data-pagination="true"
				data-side-pagination="server"
				data-page-list="[5, 10, 20, 50, 100, 200]"
				data-sort-order="asc"
			>				
				<thead>
					<tr>
						<th data-field="user.name" data-sortable="true" data-formatter="enrollmentFormatter" class="col-md-2"><?=__('user.field.name')?></th>
						<th data-field="points"  class="col-md-1">∆ <?=__('session.field.point_plural')?></th>
						<th data-field="guests"  data-sortable="true" class="col-md-2"><?=__('session.field.guest_plural')?></th>
						<?php if(! $session->settled) {?><th data-field="actions" data-formatter="actionFormatter" data-events="actionEvents" class="col-md-2"><?=__('actions.name')?></th><?php } ?>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal dialog for enrollment deletion -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="delete-enrollment-form" 
				  action="/api/v1//sessions/<?=$session->id?>/enrollments/" 
				  method="delete"
				  data-alert-error="<?=__('session.alert.error.remove_enroll')?>"
				  data-alert-success="<?=__('session.alert.success.remove_enroll')?>"
			>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.remove_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.remove_enroll.msg')?> <strong><span id="delete-user-name"></span></strong>?</p>
					<div class="form-group">
						<input id="delete-user-id" type="hidden" class="form-control" name="enrollment_id">
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
			<form id="edit-enrollment-form" 
				  action="/api/v1/sessions/<?=$session->id?>/enrollments/" 
				  method="put"
				  data-alert-error="<?=__('session.alert.error.update_enroll')?>"
				  data-alert-success="<?=__('session.alert.success.update_enroll')?>"
			>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.edit_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.edit_enroll.msg')?> <strong><span id="edit-user-name"></span></strong>.</p>
					<div class="form-group">
						<input id="edit-user-id" type="hidden" class="form-control" name="user_id">
						<label for="edit-guests"><?=__('session.fied.guest_plural')?> </label>
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
			<form id="add-enrollment-form" 
				  action="/api/v1/sessions/<?=$session->id?>/enrollments/" 
				  method="post"
				  data-alert-error="<?=__('session.alert.error.create_enroll')?>"
				  data-alert-success="<?=__('session.alert.success.create_enroll')?>"	  
			>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.create_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.create_enroll.msg')?></p>					
					<div class="form-group">
						<label for="add-user-id"><?=__('user.name')?>:</label>
						<select class="form-control" id="add-user-id-combobox" name="user_id">
							
						</select>
					</div>	
					<br>
					<div class="form-group">
						<label for="add-guests"><?=__('session.field.guest_plural')?></label>
						<input id="add-guests" name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="0"/>
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