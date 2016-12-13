<?php

$deadline = date('H:i', strtotime($session->deadline));

if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline)) && $session->can_enroll()) { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.deadline.changed', array('time' => $deadline))?>
	</div>
<?php } ?>

<div class="well">
<?=$session->notes?>
</div>

<?php if($session->can_enroll()) { ?>
<form action="/sessions/enrollments/create/<?=$session->date?>" method="post" >
	<div class="form-group">
		<label for="guests"><?=__('session.view.label.guests')?> </label>
		<input name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="0"/>
		<?php if ($session->can_enroll_cooks()) { ?>
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
