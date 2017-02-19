<?php 
$context = \Sessions\Context_Sessions::forge($session);
$enrollments = $session->get_enrollments(); 
$cur_enrollment = $session->current_enrollment();
$deadline = (new DateTime($session->deadline))->format('H:i');
$has_action = false;


$paid_by = $session->paid_by;
if(empty($paid_by)) {
	$paid_by = $current_user->id;
}

$enroll_options = [];
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
				<a class="list-group-item" href="/sessions/view/<?=$session->date?>"><i class="fa fa-eye" aria-hidden="true"></i> View original</a>
				<?php if(!$session->settled) { ?>
				<a class="list-group-item" onClick="showAddModal(
					<?=(int)$context->view_enroll_create()[1]?>, 
					<?=(int)$context->view_enroll_create()[2]?>
				)" href="#"><i class="fa fa-plus" aria-hidden="true"></i>  
					<?=__('session.view.btn.add_enroll')?>
				</a>
				<?php } ?>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('session.name')?></div>			
			<div class="panel-body">
				<?php if(!$session->settled) { ?>
				<!-- Editable session properties -->
				<?=Form::open('/sessions/update/'. $session->date)?>	
				
				<div class="form-group form-group-sm">
					<?=Form::label(__('session.field.notes'), 'notes')?>
					<?=Form::textarea('notes', $session->notes, ['class' => 'form-control'])?>
				</div>	

				<div class="form-group form-group-sm">
					<?=Form::label(__('session.field.deadline'), 'deadline')?>
					<?=Form::input('deadline', $deadline, ['type' => 'text', 'id' => 'deadline', 'class' => 'timepicker form-control', 'size' => '5'])?>
				</div>	

				<div class="form-group form-group-sm">
					<?=Form::label(__('product.field.cost'), 'cost')?>
					<div class="input-group">
						<div class="input-group-addon">€</div>
						<?=Form::input('cost', $session->cost, ['class' => 'form-control', 'type' => 'number', 'max' => 100, 'min' => 0, 'step' => '0.01', 'style' => 'z-index: 0;'])?>
					</div>
				</div>	

				<div class="form-group form-group-sm">
					<?=Form::label(__('product.field.paid_by'), 'payer-id')?>
					<?=Form::select('payer-id', $paid_by, $enroll_options, ['class' => 'form-control'])?>	
				</div>	

				
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
				<?php 			
					foreach($enrollments as $enrollment){ ?>
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
						<td>			
							<a href="#" onclick="showEditModal(
										<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>', 
										<?=$enrollment->guests?>, <?=$enrollment->cook?>, 
										<?=$enrollment->dishwasher?>,
										<?=(int)$context->view_enroll_update($enrollment->user->id)[1]?>, 
										<?=(int)$context->view_enroll_update($enrollment->user->id)[2]?>
									)"><span class="fa fa-pencil"></span> <?=__('actions.edit')?>
							</a>  |
							<a href="#" onclick="showDeleteModal(<?=$enrollment->user->id?>, '<?=$enrollment->user->name?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?=View::forge('modals', ['session' => $session])->render()?>

