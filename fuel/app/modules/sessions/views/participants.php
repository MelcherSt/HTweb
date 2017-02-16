<?php
$enrollments = $session->get_enrollments(); 
$context = \Sessions\Context_Sessions::forge($session);

foreach($session->get_unenrolled() as $user) {
	$options[$user->id] = $user->get_fullname();
}
?>
<?php if ($context->view_enroll_other()) {?>
		<button type="button" class="btn btn-primary pull-right" onClick="showAddModal(
					<?=(int)$context->view_enroll_create()[1]?>, 
					<?=(int)$context->view_enroll_create()[2]?>
				)"><span class="fa fa-user-plus"></span>
		 <?=__('session.view.btn.add_enroll')?>
		</button>
	<?php } ?>
<h3><?=__('session.role.participant_plural')?></h3>
<p><?=__('session.view.msg', ['p_count' => $session->count_total_participants(), 'g_count' => $session->count_guests()])?></p>	
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th><?=__('user.field.name')?></th>
				<th>∆ <?=__('session.field.point_plural')?></th>
				<th><?=__('session.field.guest_plural')?></th>	
				<?php if ($context->view_enroll_other()){ ?>
				<th><?=__('actions.name')?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
		<?php 			
			foreach($enrollments as $enrollment){ ?>
			<tr>
				<td><?=$enrollment->user->get_fullname()?> 
				<?php 
				if($enrollment->later) : ?>
					*
				<?php endif;
				if ($enrollment->cook): ?>
					<span class="fa fa-cutlery"></span> 
				<?php endif; 
				if ($enrollment->dishwasher): ?>
					<span class="fa fa-shower"></span> 
				<?php endif; ?>
					
				</td>
				<td><?=$enrollment->get_point_prediction()?>  </td>
				<td><?=$enrollment->guests?></td>
				<?php if ($context->view_enroll_other()): ?>
				<td>			
					<a href="#" onclick="showEditModal(
								<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>', 
								<?=$enrollment->guests?>, <?=$enrollment->cook?>, 
								<?=$enrollment->dishwasher?>,
								<?=(int)$context->view_enroll_update($enrollment->user->id)[1]?>, 
								<?=(int)$context->view_enroll_update($enrollment->user->id)[2]?>
							)"><span class="fa fa-pencil"></span> <?=__('actions.edit')?></a>  
					<?php if ($current_user->id != $enrollment->user_id): ?> |
					<a href="#" onclick="showDeleteModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
					<?php endif; ?>
				</td>
				<?php endif; ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>


<!-- Modal dialog for enrollment deletion -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/sessions/enrollments/delete')?>
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
			<?=Form::open('/sessions/enrollments/update/'. $session->date)?>
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
						<?=Form::input('guests', null, ['id' => 'edit-guests', 'class' => 'form-control', 'type' => 'number', 'max' => Sessions\Model_Session::MAX_GUESTS, 'min' => 0])?>
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
						<?=Form::select('user-id', null, $options, ['class' => 'form-control'])?>
					</div>	
					<br>
					<div class="form-group">
						<?=Form::label(__('session.field.guest_plural'), 'add-guests')?>
						<?=Form::input('guests', null, ['id' => 'add-guests', 'class' => 'form-control', 'placeholder' => 0, 'type' => 'number', 'max' => Sessions\Model_Session::MAX_GUESTS, 'min' => 0])?>
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