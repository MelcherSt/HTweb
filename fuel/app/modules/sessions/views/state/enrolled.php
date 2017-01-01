	<?php
	$deadline = date('H:i', strtotime($session->deadline)); 
	$context = \Sessions\Auth_Context_Session::forge($session, $current_user);
	$enroll = $context->has_access(['enroll.create']);
	
	// Show deadline changed alert
	if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline)) && $enroll) { ?>
		<div class="alert alert-info">
			<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.deadline.changed', array('time' => $deadline))?>
		</div>
	<?php } 
	
	// Show deadline passed alert
	if (!$enroll) { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.info')?></strong> <?=__('session.alert.deadline.passed')?>
		<?php
		if($context->has_access(['enroll.other'])) {
			echo __('session.alert.deadline.cook_edit');
		} else {
			echo __('session.alert.deadline.no_leave');
		}
		?>
	</div>
	<?php }
	
	// Show notes well
	if(!$context->has_access(['session.update[notes]'])) { ?>
	<div class="well">
		<?=$session->notes?>
	</div>
	<?php }

	// Show session update form
	if($context->has_access(['session.update'])) { ?>
	<form action="/sessions/update/<?=$session->date?>" method="post" >
		<?php if($context->has_access(['session.update[notes]'])) { ?>
		<div class="form-group">
			<label for="comment"><?=__('session.field.notes')?></label>
			<textarea name="notes" class="form-control" rows="3"><?=$session->notes?></textarea>
		</div>
		<?php } 

		if($context->has_access(['session.update[deadline]'])) { ?>
		<div class="form-group pull-right">
			<label for="deadline"><?=__('session.field.deadline')?></label>
			<input class="timepicker" name="deadline" type="text" id="deadline" maxlength="5" max="5" size="10" value="<?=$deadline?>"required/>
		</div>
		<?php }

		if ($context->has_access(['session.update[cost]'])) { ?>
		<div class="form-group pull-right">
			<label for="deadline"><?=__('session.field.cost')?></label>
			<input name="cost" type="number" step="0.01" max="100" min="0" value="<?=$session->cost?>"required/>
		</div>
		<?php } ?>	
		<button class="btn btn-primary" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update_session')?></button>
	</form>
	<br>
	<?php } 

	// Show enrollment update form
	if($enroll) { ?>

		<form action="/sessions/enrollments/update/<?=$session->date?>" method="post" >
			<div class="form-group">
				<label for="guests"><?=__('session.view.label.guests')?> </label>
				<input name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="<?=$enrollment->guests?>"/>

				<div class="checkbox">
					<label><input name="later" type="checkbox" <?=$enrollment->later ? 'checked' : ''?> > <?=__('session.view.label.later')?></label>
				</div>


				<?php if ($context->has_access(['enroll.update[cook]'])) { ?>
				<div class="checkbox">
					<label><input name="cook" type="checkbox" <?=$enrollment->cook ? 'checked' : ''?> > <?=__('session.view.label.cook')?></label>
				</div>
				<?php } ?>
			</div>

			<button class="btn btn-primary pull-left" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update_enrollment')?></button>
		</form> 

		<form action="/sessions/enrollments/delete/<?=$session->date?>" method="post" >
			<button class="btn btn-danger pull-right" type="submit"><span class="fa fa-sign-out"></span> <?=__('session.view.btn.unenroll')?></button>
		</form> 


	<?php } 

	if ($context->has_access(['enroll.update[dishwasher]'])) { ?>
		<div class="alert alert-warning">
			<strong><?=__('alert.call.alert')?></strong> <?=__('session.alert.dishes')?>
		</div>

		<form action="/sessions/enrollments/update/<?=$session->date?>" method="post" >
			<input type="hidden" value="dishwasher" name="method"/>
			<button class="btn <?=$enrollment->dishwasher ? 'btn-danger' : 'btn-primary'?>" type="submit" >
				<span class="fa fa-tint"></span> 
				<?=$enrollment->dishwasher ? __('session.view.btn.unenroll_dish') : __('session.view.btn.enroll_dish')?>
			</button>
			
			<?php if(!$enrollment->dishwasher) { ?>
				<input name="dishwasher" type="hidden" value="on"/>
			<?php } ?>
		</form>
	<?php } ?>

	<!-- TODO: externalize -->
	<script>
	$(document).ready(function(){
		// Initialization for timepicker autocomplete script
		$('input.timepicker').timepicker({
			timeFormat: 'H:mm',
			interval: 30,
			minTime: '16:00',
			maxTime: '20:00',
			startTime: '16:00',
			dynamic: false,
			dropdown: true,
			scrollbar: true
		});
	});
	</script>





