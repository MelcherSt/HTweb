<?php
$deadline = date('H:i', strtotime($session->deadline));

if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline)) && $session->can_enroll()) { ?>
	<div class="alert alert-info">
		<strong>Attention</strong> The deadline has been changed! The new deadline is today at <strong><?=$deadline?></strong>
	</div>
<?php } ?>

<div class="well">
<?=$session->notes?>
</div>

<?php if($session->can_enroll()) { ?>
<form action="/sessions/enrollments/create/<?=$session->date?>" method="post" >
	<div class="form-group">
		<label for="guests">I'm bringing guests </label>
		<input name="guests" type="number" step="1" max="10" min="0" value="0"/>
		<?php if ($session->can_enroll_cooks()) { ?>
		<div class="checkbox">
			<label><input name="cook" type="checkbox"> I feel like cooking</label>
		</div>			
		<?php } ?>
	</div>
	<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Join session</button>
</form> 
<?php} else { ?>
<div class="alert alert-info">
	<strong>Heads up!</strong> The deadline has passed. You are no longer able to join this session.
</div>
<button type="button" class="btn btn-success disabled" ><span class="fa fa-sign-in"></span> Join session</button>
<?php }
