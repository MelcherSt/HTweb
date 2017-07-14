<?php
$unenrolled_options = [];
foreach($session->get_unenrolled() as $user) {
	$unenrolled_options[$user->id] = $user->get_fullname();
}
?>

<!-- Modal dialog for session to product conversion -->
<div id="convert-session-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/sessions/convert/' . $session->date)?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.convert.title')?></h4>
				</div>
				<div class="modal-body">
					<?=__('session.modal.convert.msg')?>
				</div>
				<div class="modal-footer">	
					<?=Form::submit(['value'=> __('session.modal.convert.btn'), 'name'=>'submit', 'class' => 'btn btn-danger'])?>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>

<!-- Modal dialog for enrollment deletion -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/sessions/enrollments/delete/' . $session->date)?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.remove_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.remove_enroll.msg')?> <strong><span id="delete-user-name"></span></strong>?</p>
					<?=Form::hidden('user-id', null, ['id' => 'delete-user-id'])?>
				</div>
				<div class="modal-footer">	
					<?=Form::submit(['value'=> __('session.modal.remove_enroll.btn'), 'name'=>'submit', 'class' => 'btn btn-primary'])?>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>

<!-- Modal dialog for enrollment editing -->
<div id="edit-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/sessions/enrollments/update/' . $session->date)?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.edit_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.edit_enroll.msg')?> <strong><span id="edit-user-name"></span></strong>.</p>
					<div class="form-group">
						<?=Form::hidden('user-id', null, ['id' => 'edit-user-id'])?>
						<?=Form::label(__('session.field.guest_plural'), 'edit-guests')?>
						<?=Form::input('guests', 0, ['id' => 'edit-guests', 'class' => 'form-control', 'type' => 'number', 'max' => Sessions\Model_Session::MAX_GUESTS, 'min' => 0])?>
					</div>			
					<div class="form-group">
						<label><?=__('session.role.name_plural')?></label>
						<div class="checkbox">
							<label>
								<?=Form::checkbox('cook', 'on', ['id' => 'edit-cook'])?>
								<?=__('session.role.cook')?>
							</label>
						</div>
						<div class="checkbox">
							<label>
								<?=Form::checkbox('dishwasher', 'on', ['id' => 'edit-dishwasher'])?>
								<?=__('session.role.dishwasher')?>
							</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?=Form::submit(['value'=> __('session.modal.edit_enroll.btn'), 'name'=>'submit', 'class' => 'btn btn-primary'])?>
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>

<!-- Modal dialog for enrollment creation -->
<div id="add-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/sessions/enrollments/create/'. $session->date)?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.create_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.create_enroll.msg')?></p>					
					<div class="form-group">
						<?=Form::label(__('user.name'), 'user-id')?>
						<?=Form::select('user-id', null, $unenrolled_options, ['class' => 'form-control'])?>
					</div>	
					<br>
					<div class="form-group">
						<?=Form::label(__('session.field.guest_plural'), 'add-guests')?>
						<?=Form::input('guests', 0, ['id' => 'add-guests', 'class' => 'form-control', 'placeholder' => 0, 'type' => 'number', 'max' => Sessions\Model_Session::MAX_GUESTS, 'min' => 0])?>
					</div>
					
					<div class="form-group">
						<label><?=__('session.role.name_plural')?></label>
						<div class="checkbox">
							<label>
								<?=Form::checkbox('cook', 'on', ['id' => 'add-cook'])?>
								<?=__('session.role.cook')?>
							</label>
						</div>
						<div class="checkbox">
							<label>
								<?=Form::checkbox('dishwasher', 'on', ['id' => 'add-dishwasher'])?>
								<?=__('session.role.dishwasher')?>
							</label>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<?=Form::submit(['value'=> __('session.modal.create_enroll.btn'), 'name'=>'submit', 'class' => 'btn btn-primary'])?>
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>
