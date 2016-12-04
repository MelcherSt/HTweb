<?php
$enrollment = $session->current_enrollment();
$deadline = date('H:i', strtotime($session->deadline));

if (\Sessions\Model_Session::DEADLINE_TIME != date('H:i', strtotime($session->deadline)) && $session->can_enroll()) { ?>
	<div class="alert alert-info">
		<strong>Attention</strong> The deadline has been changed! The new deadline is today at <strong><?=$deadline?></strong>
	</div>
<?php 
}

if(isset($enrollment)) {
	if(!$enrollment->cook) { ?>
		<div class="well">
		<?=$session->notes?>
		</div>
	<?php
	} 
	
	// When able to enroll, show form
	if($session->can_enroll()) { ?>

	<form action="/sessions/enroll/<?=$session->date?>" method="post" >
		
		<?php if($enrollment->cook): ?>
		<div class="form-group">
			<label for="comment">Notes</label>
			<textarea name="notes" class="form-control" rows="3"><?=$session->notes?></textarea>
		</div>
		
		<div class="form-group pull-right">
			<label for="deadline">Deadline </label>
			<input class="timepicker" name="deadline" type="text" id="deadline" maxlength="5" max="5" size="10" value="<?=$deadline?>"required/>
		</div>	
		<?php endif; ?>
		
		<div class="form-group">
			<label for="guests">I'm bringing guests </label>
			<input name="guests" type="number" step="1" max="10" min="0" value="<?=$enrollment->guests?>"/>
			
			<?php if ($session->can_enroll_cooks() || $enrollment->cook): ?>
			<div class="checkbox">
				<label><input name="cook" type="checkbox" <?=$enrollment->cook ? 'checked' : ''?> > I feel like cooking</label>
			</div>
			<?php endif; ?>
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
		if($enrollment->cook) { ?>
		<div class="alert alert-info">
			<strong>Heads up!</strong> The deadline has passed. For a limited amout of time you will be able to 
			change the participation of users in this session and enter the cost. Additionally, you may change
			the deadline if you feel this is necessary. 		
		</div>
	
		<form action="/sessions/enroll/<?=$session->date?>" method="post" > 
			<input type="hidden" name="guests" value="<?=$enrollment->guests?>"/>
			<input type="hidden" name="cook" value="on"/>
			<?php if($session->can_change_deadline()): ?>
			<div class="form-group pull-right">
				<label for="deadline">Deadline</label>
				<input class="timepicker" name="deadline" type="text" id="deadline" maxlength="5" max="5" size="10" value="<?=$deadline?>"required/>
			</div>
			<?php endif;
			if ($session->can_change_cost()): ?>
			<div class="form-group pull-right">
				<label for="deadline">Cost (in €)</label>
				<input name="cost" type="number" step="0.1" max="100" min="0" value="<?=$session->cost?>"required/>
			</div>
			<?php endif; ?>
			
			<button class="btn btn-success" type="submit" ><span class="fa fa-sign-in"></span> Update session</button>
		</form> 		
		<?php
		} else { ?>
		<div class="alert alert-info">
			<strong>Heads up!</strong> You can no longer change your participation in this session. The deadline has passed.
		</div>
		<?php
		}
	}
	
	if ($session->can_enroll_dishwashers()) { ?>
		<br>
		<div class="alert alert-warning">
			<strong>Hey you!</strong> Did you do the dishes? Click the button to join the dishwashers for this session.
		</div>
	
		<form action="/sessions/enroll/<?=$session->date?>" method="post" >
			<input type="hidden" value="dishwasher" name="method"/>
			<?php if($enrollment->dishwasher) { ?>
			<button class="btn btn-danger" type="submit" ><span class="fa fa-tint"></span> Actually, I did not do the dishes</button>
			<?php } else { ?>
			<input name="dishwasher" type="hidden" value="on"/>
			<button class="btn btn-primary" type="submit" ><span class="fa fa-tint"></span> I did the dishes</button>
			<?php } ?>
		</form>
	<?php
	}
} else { ?>
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
			<?php if ($session->can_enroll_cooks()) { ?>
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
		<strong>Heads up!</strong> The deadline has passed. You are no longer able to join this session.
	</div>
	
	<button type="button" class="btn btn-success disabled" ><span class="fa fa-sign-in"></span> Join session</button>
	
	<?php
	}
}?>

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





