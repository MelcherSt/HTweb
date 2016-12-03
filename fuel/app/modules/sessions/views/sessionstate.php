<?php
$enrollment = $session->current_enrollment();
$deadline = date('H:i', strtotime($session->deadline));

// Gotta love the debbuging
/*echo 'DBG cooks: ' . $session->count_cooks();
echo '<br>';
echo 'DBG dishwashers: ' . $session->count_dishwashers();
*/

if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline))) { ?>
	<div class="alert alert-info">
		<strong>Attention</strong> This session's deadline has been changed! The deadline is: <strong><?=$deadline?></strong>
	</div>
<?php }

if(isset($enrollment)) {
	// User is enrolled

	// Show well with notes to non-cooks (cooks have notes in form)
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
		
		<div class="form-group pull-right">
			<label for="deadline">Deadline </label>
			<input class="timepicker" name="deadline" type="text" id="deadline" value="<?=$deadline?>"required/>
		</div>	
		
		<?php } ?>
		
		<div class="form-group">
			<label for="guests">I'm bringing guests </label>
			<input name="guests" type="number" step="1" max="10" min="0" value="<?=$enrollment->guests?>"/>
			
			<?php if ($session->can_enroll_cooks() || $enrollment->cook) { ?>
			<div class="checkbox">
				<label><input name="cook" type="checkbox" <?=$enrollment->cook ? 'checked' : ''?> > I feel like cooking</label>
			</div>
			<?php }	?>
		</div>
		
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Update session</button>
	</form> 
	<br>
	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		<input type="hidden" name="method" value="delete"/>
		<button class="btn btn-danger" type="submit"><span class="fa fa-sign-out"></span> Leave session</button>
	</form> 

	<?php
	} else {
	?>
	
	<div class="alert alert-info">
		<strong>Heads up!</strong> This session can only be edited by cooks since it's past its enrollment deadline.
	</div>

		<?php
		if($enrollment->cook) { ?>
		<form action="/sessions/enroll/<?=$session->date?>" method="post" > 
			
			<?php if($session->can_change_deadline()) { ?>
			<div class="form-group pull-right">
				<label for="deadline">Deadline </label>
				<input class="timepicker" name="deadline" type="text" id="deadline" value="<?=$deadline?>"required/>
			</div>
			<?php }
			
			if ($session->can_change_cost()) { ?>
			<div class="form-group pull-right">
				<label for="deadline">Cost </label>
				<input name="cost" type="number" step="0.1" max="100" min="0" value="<?=$session->cost?>"required/>
			</div>
			
			<?php
			}
			?>
			
			<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Update session</button>
		</form> 		
		<?php
		}
	}
	
	if ($session->can_enroll_dishwashers()) { ?>
		<div class="alert alert-warning">
			<strong>Wait a sec!</strong> Did you do the dishes? Click the button to join the dishwashers for this session.
		</div>
	
		<form action="/sessions/enroll/<?=$session->date?>" method="post" >
			<?php if($enrollment->dishwasher) { ?>
			<button class="btn btn-danger" type="submit" ><span class="fa fa-tint"></span> Actually, I did not do the dishes</button>
			<?php } else { ?>
			<input name="dishwasher" type="hidden" value="on"/>
			<button class="btn btn-primary" type="submit" ><span class="fa fa-tint"></span> I did the dishes</button>
			<?php } ?>
		</form>
	<?php
	}
	

} else {	
	// User is not enrolled (yet)
	
	// Show static notes?>
	<div class="well">
	<?=$session->notes?>
	</div>

	
	<?php if($session->can_enroll()) { 
		// Show enrollment form
	?>

	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		<div class="form-group">
			<label for="email">I'm bringing guests </label>
			<input name="guests" type="int" step="1" max="10" min="0" value="0"/>
			<?php if ($session->can_enroll_cooks()) { 
				// Is the max # of cooks reached?
			?>
			<div class="checkbox">
				<label><input name="cook" type="checkbox"> I feel like cooking</label>
			</div>
			<?php } ?>
		</div>
		<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Join session</button>
	</form> 

	<?php
	} else {
	?>
	
	<div class="alert alert-info">
		<strong>Heads up!</strong> This session is past its enrollment deadline. You are no longer able to join this session.
	</div>
	
	<button type="button" class="btn btn-success disabled" ><span class="fa fa-sign-in"></span> Join session</button>
	
	<?php
	}
}?>

<!-- TODO: externalize -->
<script>
$(document).ready(function(){
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





