<?php

$deadline = date('H:i', strtotime($session->deadline));

if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline)) && $session->can_enroll()) { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.deadline.changed', array('time' => $deadline))?>
	</div>
<?php } 

if(!$enrollment->cook) { ?>
	<div class="well">
	<?=$session->notes?>
	</div>
<?php } 

// When able to enroll, show form
if($session->can_enroll()) { ?>

<form action="/sessions/enrollments/update/<?=$session->date?>" method="post" >

	<?php if($enrollment->cook): ?>
	<div class="form-group">
		<label for="comment"><?=__('session.field.notes')?></label>
		<textarea name="notes" class="form-control" rows="3"><?=$session->notes?></textarea>
	</div>

	<div class="form-group pull-right">
		<label for="deadline"><?=__('session.field.deadline')?> </label>
		<input class="timepicker" name="deadline" type="text" id="deadline" maxlength="5" max="5" size="8" value="<?=$deadline?>"required/>
	</div>	
	<?php endif; ?>

	<div class="form-group">
		<label for="guests"><?=__('session.view.label.guests')?> </label>
		<input name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="<?=$enrollment->guests?>"/>

		<?php if ($session->can_enroll_cooks() || $enrollment->cook): ?>
		<div class="checkbox">
			<label><input name="cook" type="checkbox" <?=$enrollment->cook ? 'checked' : ''?> > <?=__('session.view.label.cook')?></label>
		</div>
		<?php endif; ?>
	</div>

	<button class="btn btn-success" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update')?></button>
</form> 
<br>
<form action="/sessions/enrollments/delete/<?=$session->date?>" method="post" >
	<button class="btn btn-danger" type="submit"><span class="fa fa-sign-out"></span> <?=__('session.view.btn.unenroll')?></button>
</form> 

<?php } else {
	if($enrollment->cook) { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.info')?></strong> <?=__('session.alert.deadline.passed')?> <?=__('session.alert.deadline.cook_edit')?>
	</div>

	<form action="/sessions/enrollments/update/<?=$session->date?>" method="post" > 
		<input type="hidden" name="guests" value="<?=$enrollment->guests?>"/>
		<input type="hidden" name="cook" value="on"/>
		
		<?php if($session->can_change_deadline()): ?>
		<div class="form-group pull-right">
			<label for="deadline"><?=__('session.field.deadline')?></label>
			<input class="timepicker" name="deadline" type="text" id="deadline" maxlength="5" max="5" size="10" value="<?=$deadline?>"required/>
		</div>
		<?php endif;
		
		if ($session->can_change_cost()): ?>
		<div class="form-group pull-right">
			<label for="deadline"><?=__('session.field.cost')?> (in €)</label>
			<input name="cost" type="number" step="0.1" max="100" min="0" value="<?=$session->cost?>"required/>
		</div>
		<?php endif; ?>
		
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> <?=__('session.view.btn.update')?></button>
	</form> 	

	<?php } else { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.info')?></strong> <?=__('session.alert.deadline.passed')?> <?=__('session.alert.deadline.no_leave')?> 
	</div>
	<?php }
}

if ($session->can_enroll_dishwashers()) { ?>
	<br>
	<div class="alert alert-warning">
		<strong><?=__('alert.call.alert')?></strong> <?=__('session.alert.dishes')?>
	</div>

	<form action="/sessions/enrollments/update/<?=$session->date?>" method="post" >
		<input type="hidden" value="dishwasher" name="method"/>
		<?php if($enrollment->dishwasher) { ?>
		<button class="btn btn-danger" type="submit" ><span class="fa fa-tint"></span> <?=__('session.view.btn.unenroll_dish')?></button>
		<?php } else { ?>
		<input name="dishwasher" type="hidden" value="on"/>
		<button class="btn btn-primary" type="submit" ><span class="fa fa-tint"></span> <?=__('session.view.btn.enroll_dish')?></button>
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





