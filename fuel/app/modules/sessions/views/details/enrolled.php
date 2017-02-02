	<?php
	$deadline = date('H:i', strtotime($session->deadline)); 
	$old_context = \Sessions\Auth_Context_Session::forge($session, $current_user);
	$enroll = $old_context->has_access(['enroll.create']);
	
	$context = new \Sessions\Auth_SessionContext($session);
	
	// Show deadline changed alert
	if($context->canview_session(\Sessions\Auth_SessionUIItem::ALERT_DEADLINE_CHANGED)) { ?>
		<div class="alert alert-info">
			<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.deadline.changed', array('time' => $deadline))?>
		</div>
	<?php } ?>
	
	<div class="panel panel-default">
		<div class="panel-heading"><?=__('session.view.enrollment')?></div>
		<div class="panel-body">
			<?php if($context->canview_session(\Sessions\Auth_SessionUIItem::BTN_ENROLL)) { ?>
			<form id="add-enrollment-form" 
				  action="/api/v1/sessions/<?=$session->id?>/enrollments/" 
				  method="post"
				  data-alert-error="<?=__('session.alert.error.create_enroll')?>"
				  data-alert-success="<?=__('session.alert.success.create_enroll')?>"	  
			>
				<div class="form-group">
					<label for="add-guests"><?=__('session.field.guest_plural')?></label>
					<input id="add-guests" name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="0"/>
				</div>

				<div class="form-group">
					<label><?=__('session.role.name_plural')?></label>
					<div class="checkbox">
						<label><input id="add-cook" name="cook" type="checkbox"><?=__('session.role.cook')?></label>
					</div>
					<div class="checkbox">
						<label><input id="add-dishwasher" name="dishwasher" type="checkbox"><?=__('session.role.dishwasher')?></label>
					</div>
				</div>
				<input type="submit" class="btn btn-primary btn-sm" value="<?=__('session.view.btn.enroll')?>" />
			</form>
			<?php } ?>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><?=__('actions.properties')?></div>
		<div class="panel-body">
		<?php if($context->canview_session(\Sessions\Auth_SessionUIItem::BTN_SESSION_UPDATE)) { ?>	
		<form id="update-session-form" 
			  action="/api/v1/sessions/<?=$session->id?>" 
			  method="put"
			  data-alert-success="<?=__('session.alert.success.update_session')?>"
			  data-alert-error="<?=__('session.alert.error.update_session')?>"
		>
			<div class="form-group ">
				<textarea name="notes" class="form-control" rows="2" placeholder="<?=__('session.field.notes')?>"><?=$session->notes?></textarea>
			</div>

			<div class="form-group">
				<label for="deadline"><?=__('session.field.deadline')?></label>
				<input class="timepicker form-control" name="deadline" type="text" id="deadline" maxlength="5" size="5" value="<?=$deadline?>"required/>
			</div>

			<div class="form-group">
				<label for="cost"><?=__('product.field.cost')?></label>
				<div class="input-group">
					<div class="input-group-addon">€</div>
					<input style="z-index: 0;" name="cost" class="form-control" type="number" step="0.01" max="100" min="0" value="<?=$session->cost?>"required/>	
				</div>
				<br>
				<label><?=__('product.field.paid_by')?></label>
				<select class="form-control" id="add-user-id" name="payer_id">
					<option value="<?=$session->paid_by?>"><?=$session->get_payer()->get_fullname()?></option>
					<!-- TODO: use XHR. For now show all active users as possible payers to avoid problems when changing enrollments -->	
					<?php foreach(\Model_User::get_by_state() as $enrollment):
							$user_id = $enrollment->id;
							if($user_id == $session->paid_by) { continue; }
					?>
					<option value="<?=$user_id?>"><?=$enrollment->get_fullname()?></option>
					<?php endforeach;  ?>
				</select>	
			</div>
			<button class="btn btn-sm btn-primary" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update_session')?></button>
		</form>
		<?php } else { ?>		
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
	
	
	
	
	
	
	
	
	
	
	
	
	<?php
	// Show deadline passed alert
	if (!$enroll && !$old_context->has_access(['session.manage'])) { ?>
	<div class="alert alert-info">
		<strong><?=__('alert.call.info')?></strong> <?=__('session.alert.deadline.passed')?>
		<?php
		if($old_context->has_access(['enroll.other'])) {
			echo __('session.alert.deadline.cook_edit');
		} else {
			echo __('session.alert.deadline.no_leave');
		}
		?>
	</div>
	
	
	<?php }

	// Show enrollment update form
	if($enroll) { ?>

	
		<form action="/sessions/enrollments/delete/<?=$session->date?>" method="post" >
			<button class="btn btn-danger pull-right" type="submit"><span class="fa fa-sign-out"></span> <?=__('session.view.btn.unenroll')?></button>
		</form> 
	<?php } 
	


	if ($old_context->has_access(['enroll.update[dishwasher]'])) { ?>
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

