<?php

$enrollment = $session->current_enrollment();

if(isset($enrollment)) {
	
	// Cook may edit notes
	if($enrollment->cook) {	?>
	<div class="form-group">
		<label for="comment">Notes</label>
		<textarea name="notes" class="form-control" rows="3"><?=$session->notes?></textarea>
	</div>

	<?php
	} else { ?>
	<div class="well">
	<?=$session->notes?>
	</div>
	<?php
	} 
	
	if($session->can_enroll()) { ?>

	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		<input type="hidden" name="method" value="delete"/>
		<button class="btn btn-danger" type="submit" ><span class="fa fa-sign-out"></span> Leave session</button>
	</form> 

	<?php
	}
} else {	
	// Show static notes?>
	<div class="well">
	<?=$session->notes?>
	</div>
	
	<?php if($session->can_enroll()) { ?>

	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Join session</button>
	</form> 

	<?php
	}
}


