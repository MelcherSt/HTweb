<?php
$enrollment = $session->current_enrollment();

if(isset($enrollment)) {

	// Show well with notes
	if(!$enrollment->cook) { ?>
		<div class="well">
		<?=$session->notes?>
		</div>
	<?php
	} 
	
	// When able to enroll, show form
	if($session->can_enroll()) { ?>

	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		
		<?php if($enrollment->cook) { ?>
		<div class="form-group">
			<label for="comment">Notes</label>
			<textarea name="notes" class="form-control" rows="3"><?=$session->notes?></textarea>
		</div>
		<?php } ?>
		
		<div class="form-group">
			<label for="email">I'm bringing guests </label>
			<input name="guests" type="int" step="1" max="10" min="0" value="<?=$enrollment->guests?>"/>
			
			<?php if ($session->count_cooks() < Sessions\Model_Session::MAX_COOKS) { ?>
			<div class="checkbox">
				<label><input name="cook" type="checkbox" <?=$enrollment->cook ? 'checked' : ''?> > I feel like cooking</label>
			</div>
			<?php } ?>
		</div>
		
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Update session</button>
	</form> 
	<br>
	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		<input type="hidden" name="method" value="delete"/>
		<button class="btn btn-danger" type="submit"><span class="fa fa-sign-out"></span> Leave session</button>
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
		<div class="form-group">
			<label for="email">I'm bringing guests </label>
			<input name="guests" type="int" step="1" max="10" min="0" value="0"/>
			<?php if ($session->count_cooks() < 2) { ?>
			<div class="checkbox">
				<label><input name="cook" type="checkbox"> I feel like cooking</label>
			</div>
			<?php } ?>
		</div>
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Join session</button>
	</form> 

	<?php
	}
}

if(!$session->can_enroll()) {
	?>
	
	<div class="alert alert-info">
		<strong>Heads up!</strong> This session is past its enrollment deadline. You are no longer able to join this session or make changes to it.
	</div>
	
	<?php
		
	if ($session->can_enroll_dishwashers()) { ?>
		<form action="/sessions/enroll/<?=$session->date?>" method="post" >
			<input name="dishwasher" type="hidden" value="on"/>
			<button class="btn btn-primary" type="submit" ><span class="fa fa-tint"></span> I did the dishes</button>
		</form>
	<?php
	}
}


