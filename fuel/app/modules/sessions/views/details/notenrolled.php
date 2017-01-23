<?php
	$deadline = date('H:i', strtotime($session->deadline));
	$context = \Sessions\Auth_Context_Session::forge($session, $current_user);
	$enroll = $context->has_access(['enroll.create']);
	
	// Show deadline changed alert
	if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline)) && $enroll) { ?>
		<div class="alert alert-info">
			<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.deadline.changed', array('time' => $deadline))?>
		</div>
	<?php } ?>

	<div class="well">
		<?=$session->notes?>
	</div>

	<?php if($context->has_access(['enroll.create'])) { ?>
	<form action="/sessions/enrollments/create/<?=$session->date?>" method="post" >
		<div class="form-group">
			<label for="guests"><?=__('session.view.label.guests')?> </label>
			<input name="guests" class="form-control" type="number" step="1" maxlength="2" size="3" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" placeholder="0"/>


			<div class="checkbox">
				<label><input name="later" type="checkbox"> <?=__('session.view.label.later')?></label>
			</div>

			<?php if ($context->has_access(['enroll.create[cook]'])) { ?>
			<div class="checkbox">
				<label><input name="cook" type="checkbox"> <?=__('session.view.label.cook')?></label>
			</div>			
			<?php } ?>
		</div>
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> <?=__('session.view.btn.enroll')?></button>
	</form> 
	<?php } else { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.info')?></strong> <?=__('session.alert.deadline.passed')?> <?=__('session.alert.deadline.no_join')?>
	</div>
	<button type="button" class="btn btn-success disabled" ><span class="fa fa-sign-in"></span> <?=__('session.view.btn.enroll')?></button>
	<?php } 

