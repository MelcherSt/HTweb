<?php
$enrollments = $session->get_enrollments(); 
$cur_enrollment = $session->current_enrollment();
$context = \Sessions\Context_Sessions::forge($session);
$deadline = (new DateTime($session->deadline))->format('H:i');
$has_action = false;
$view_update = $context->view_update();
$view_enroll_create = $context->view_enroll_create();
$view_enroll_update = $context->view_enroll_update();

$paid_by = $session->paid_by;
if(empty($paid_by)) {
	$paid_by = $current_user->id;
}

foreach($enrollments as $enrollment) {
	$user = $enrollment->user;
	$enroll_options[$user->id] = $user->get_fullname();
}
?>

<div class="row">	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<?php if($context->view_enroll_other()) { 
					$has_action = true;
					?>
					<a class="list-group-item" onClick="showAddModal(
						<?=(int)$context->view_enroll_create()[1]?>, 
						<?=(int)$context->view_enroll_create()[2]?>
					)" href="#"><i class="fa fa-plus" aria-hidden="true"></i>  
						<?=__('session.view.btn.add_enroll')?>
					</a>
				<?php } ?>	
			</div>
			<div class="panel-body">
				<?php if($view_enroll_create[0]) {?>
					<!-- Create enrollment -->	
					<?=Form::open('/sessions/enrollments/create/'. $session->date)?>
					<div class="form-group">
						<?=Form::label(__('session.view.label.guests'), 'add-guests')?>
						<?=Form::input('guests', null, ['class' => 'form-control', 'placeholder' => 0, 'type' => 'number', 'max' => Sessions\Model_Session::MAX_GUESTS, 'min' => 0])?>
					</div>

					<?php if($view_enroll_create[1]) { ?>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<?=Form::checkbox('cook', 'on')?>
									<?=__('session.view.label.cook')?>
								</label>
							</div>
						</div>	
					<?php } ?>
					
					<div class="form-group">
						<div class="checkbox">
							<label>
								<?=Form::checkbox('later', 'on')?>
								<?=__('session.view.label.later')?>
							</label>
						</div>
					</div>	
					<?=Form::submit(['value'=> __('session.view.btn.enroll'), 'name'=>'submit', 'class' => 'btn btn-primary'])?>	
					<?=Form::close()?>
					
				<?php } else if($view_enroll_update[0]) { ?>
					<!-- Update enrollment -->
					<?=Form::open('/sessions/enrollments/update/'. $session->date)?>
					
					<div class="form-group form-group-sm">
						<?=Form::label(__('session.view.label.guests'), 'add-guests')?>
						<?=Form::input('guests', $cur_enrollment->guests, ['class' => 'form-control', 'placeholder' => 0, 'type' => 'number', 'max' => Sessions\Model_Session::MAX_GUESTS, 'min' => 0])?>
					</div>
					
					<?php if($view_enroll_update[1]) { ?>
					<div class="form-group form-group-sm">
						<div class="checkbox">
							<label>
								<?=Form::checkbox('cook', 'on', (bool)$cur_enrollment->cook)?>
								<?=__('session.view.label.cook')?>
							</label>
						</div>
					</div>	
					<?php } ?>
					
					<div class="form-group form-group-sm">
						<div class="checkbox">
							<label>
								<?=Form::checkbox('later', 'on', (bool)$cur_enrollment->later)?>
								<?=__('session.view.label.later')?>
							</label>
						</div>
					</div>	
					
					<?=Form::submit(['value'=> __('session.view.btn.update_enrollment'), 'name'=>'submit', 'class' => 'btn btn-sm btn-block btn-primary'])?>	
					<?=Form::close()?>	
					
					<!-- Unroll -->
					<?=Form::open('/sessions/enrollments/delete/' . $session->date)?>	
					<?=Form::submit(['value'=> __('session.view.btn.unenroll'), 'name'=>'submit', 'class' => 'btn btn-sm btn-block btn-danger'])?>	
					<?=Form::close()?>
					
				<?php } else if($view_enroll_update[3]) { ?>
					<!-- Create dishwasher enrollment -->
					<div class="alert alert-warning">
						<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.dishes')?>
					</div>

					<?=Form::open('/sessions/enrollments/update/' . $session->date)?>
					<?=Form::hidden('method', 'dishwasher')?>
					<?=Form::hidden('dishwasher', ($cur_enrollment->dishwasher ? '' : 'on'))?>						
					<?=Form::submit( [
						'value' => ($cur_enrollment->dishwasher ? __('session.view.btn.unenroll_dish') : __('session.view.btn.enroll_dish')),
						'class' => 'btn btn-sm btn-block' . ' ' .  ($cur_enrollment->dishwasher ? 'btn-danger' : 'btn-primary'),
						'name' => 'submit',
						])?>								
					<?=Form::close()?>
				<?php } else if(!$has_action) { ?>
					<em><?=__('actions.no_actions')?></em>
				<?php } ?>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('session.name')?></div>			
			<div class="panel-body">
				<?php if($view_update[0]) { ?>
				<!-- Editable session properties -->
				<?=Form::open('/sessions/update/'. $session->date)?>	
				
				<?php if($view_update[1]) { ?>
				<div class="form-group form-group-sm">
					<?=Form::label(__('session.field.notes'), 'notes')?>
					<?=Form::textarea('notes', $session->notes, ['class' => 'form-control'])?>
				</div>	
				<?php } ?>
				
				<?php if($view_update[2]) { ?>
				<div class="form-group form-group-sm">
					<?=Form::label(__('session.field.deadline'), 'deadline')?>
					<?=Form::input('deadline', $deadline, ['type' => 'text', 'id' => 'deadline', 'class' => 'timepicker form-control', 'size' => '5'])?>
				</div>	
				<?php } ?>
				
				<?php if($view_update[3]) { ?>
				<div class="form-group form-group-sm">
					<?=Form::label(__('product.field.cost'), 'cost')?>
					<div class="input-group">
						<div class="input-group-addon">€</div>
						<?=Form::input('cost', $session->cost, ['class' => 'form-control', 'type' => 'number', 'max' => 100, 'min' => 0, 'step' => '0.01', 'style' => 'z-index: 0;'])?>
					</div>
				</div>	
				<?php } ?>

				<?php if($view_update[4]) { ?>
				<div class="form-group form-group-sm">
					<?=Form::label(__('product.field.paid_by'), 'payer-id')?>
					<?=Form::select('payer-id', $paid_by, $enroll_options, ['class' => 'form-control'])?>	
				</div>	
				<?php } ?>
				
				<?=Form::submit(['value'=> __('session.view.btn.update_session'), 'name'=>'submit', 'class' => 'btn btn-sm btn-primary btn-block'])?>
				<?=Form::close()?>
				<?php } else { ?>
				<!-- Static session properties -->
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
				<?php } ?>
			</div>
		</div>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<h3><?=__('session.role.participant_plural')?></h3>
		<p><?=__('session.view.msg', ['p_count' => $session->count_total_participants(), 'g_count' => $session->count_guests()])?></p>	
		<div class="table-responsive">
			<table class="table table-hover">
				<thead>
					<tr>
						<th><?=__('user.field.name')?></th>
						<th>∆ <?=__('session.field.point_plural')?></th>
						<th><?=__('session.field.guest_plural')?></th>	
						<?php if($context->view_enroll_other()){ ?>
						<th><?=__('actions.name')?></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach($enrollments as $enrollment){ ?>
					<tr>
						<td><?=$enrollment->user->get_fullname()?> 
						<?php 
						if($enrollment->later) : ?>
							*
						<?php endif;
						if($enrollment->cook): ?>
							<span class="fa fa-cutlery"></span> 
						<?php endif; 
						if($enrollment->dishwasher): ?>
							<span class="fa fa-shower"></span> 
						<?php endif; ?>

						</td>
						<td><?=$enrollment->get_point_prediction()?>  </td>
						<td><?=$enrollment->guests?></td>
						<?php if($context->view_enroll_other()) {?>
						<td>			
							<a href="#" onclick="showEditModal(
										<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>', 
										<?=$enrollment->guests?>, <?=$enrollment->cook?>, 
										<?=$enrollment->dishwasher?>,
										<?=(int)$context->view_enroll_update($enrollment->user->id)[1]?>, 
										<?=(int)$context->view_enroll_update($enrollment->user->id)[2]?>
									)"><span class="fa fa-pencil"></span> <?=__('actions.edit')?></a>  
							<?php if($current_user->id != $enrollment->user_id) { ?> |
							<a href="#" onclick="showDeleteModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
							<?php } ?>
						</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php if($context->view_enroll_other()) {
	// Render necessary modals
	echo View::forge('modals', ['session' => $session])->render();
} ?>

