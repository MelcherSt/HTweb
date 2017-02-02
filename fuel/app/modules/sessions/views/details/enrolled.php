	<?php
	$deadline = date('H:i', strtotime($session->deadline)); 
	$context = new \Sessions\Auth_SessionContext($session);
	
	$view_ses_enroll = $context->canview_session(\Sessions\Auth_SessionUIItem::BTN_ENROLL);
	$view_ses_unenroll = $context->canview_session(\Sessions\Auth_SessionUIItem::BTN_UNROLL);
	
	// Show deadline changed alert
	if($view_ses_enroll || $view_ses_unenroll) { ?>
		<div id="deadline-alert" class="alert alert-info hidden-default">
			<strong><?=__('alert.call.attention')?></strong> <?=__('session.alert.deadline.changed')?> <strong id="new-deadline"></strong>
		</div>
	<?php } ?>
	
	<input type="hidden" id="session-id" value="<?=$session->id?>">
	<input type="hidden" id="user-id" value="<?=$current_user->id?>">

	<div class="panel panel-default">
		<div class="panel-heading"><?=__('session.view.enrollment')?></div>
		<div class="panel-body">
			
			
			<div id="page-add-enrollment" class="<?=$view_ses_enroll ? '' : 'hidden-default'?>">
				<!-- Create enrollment form -->
				<form id="page-add-enrollment-form-page" 
					  action="/api/v1/sessions/<?=$session->id?>/enrollments/" 
					  method="post"
					  data-alert-error="<?=__('session.alert.error.create_enroll')?>"
					  data-alert-success="<?=__('session.alert.success.create_enroll')?>"	  
				>
					<input type="hidden" name="user_id" value="<?=$current_user->id?>">
					<div class="form-group">
						<label for="page-add-guests"><?=__('session.field.guest_plural')?></label>
						<input id="page-add-guests" name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="0"/>
					</div>

					<div class="form-group">
						<label><?=__('session.role.name_plural')?></label>
						<div class="checkbox">
							<label><input id="page-add-cook" name="cook" type="checkbox"><?=__('session.view.label.cook')?></label>
						</div>
						<div class="checkbox">
							<label><input id="page-add-later" name="later" type="checkbox"> <?=__('session.view.label.later')?></label>
						</div>
					</div>
					<input type="submit" class="btn btn-primary btn-sm" value="<?=__('session.view.btn.enroll')?>" />
				</form>
			</div>
			
			<div id="page-edit-enrollment" class="<?=$view_ses_unenroll ? '' : 'hidden-default'?>">
				<!-- Update enrollment form -->
				<form id="page-edit-enrollment-form-page" 
					  action="/api/v1/sessions/<?=$session->id?>/enrollments/" 
					  method="put"
					  data-alert-error="<?=__('session.alert.error.update_enroll')?>"
					  data-alert-success="<?=__('session.alert.success.update_enroll')?>"
				>
					<input id="page-edit-user-id" type="hidden" class="form-control" name="user_id" value="<?=$current_user->id?>">
					<div class="form-group">
						<label for="page-edit-guests"><?=__('session.field.guest_plural')?></label>
						<input id="page-edit-guests" name="guests" type="number" step="1" max="<?=\Sessions\Model_Session::MAX_GUESTS?>" min="0" value="0"/>
					</div>

					<div class="form-group">
						<div class="checkbox">
							<label><input id="page-edit-cook" name="cook" type="checkbox"><?=__('session.view.label.cook')?></label>
						</div>
						<div class="checkbox">
							<label><input id="page-edit-later" name="later" type="checkbox"> <?=__('session.view.label.later')?></label>
						</div>
					</div>
					<button class="btn btn-primary btn-sm pull-left" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update_enrollment')?></button>
				</form>

				<!-- Unroll button -->
				<form id="delete-enrollment-form-page" 
					  action="/api/v1/sessions/<?=$session->id?>/enrollments/" 
					  method="delete"
					  data-alert-error="<?=__('session.alert.error.remove_enroll')?>"
					  data-alert-success="<?=__('session.alert.success.remove_enroll')?>"
				>
					<input id="delete-user-id" type="hidden" class="form-control" value="<?=$current_user->id?>">
					<button class="btn btn-danger btn-sm pull-right" type="submit"><span class="fa fa-sign-out"></span> <?=__('session.view.btn.unenroll')?></button>
				</form>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading"><?=__('actions.properties')?> 
			<?php 
			$view_ses_upd = $context->canview_session(\Sessions\Auth_SessionUIItem::BTN_SESSION_UPDATE); 
			$view_ses_cost = $context->canview_session(\Sessions\Auth_SessionUIItem::INPUT_COST);
			$view_ses_deadline = $context->canview_session(\Sessions\Auth_SessionUIItem::INPUT_DEADLINE);
			?>	
			<button id="update-session-btn" onclick="showSessionProperties()" class="btn btn-xs pull-right <?=$view_ses_upd ? '' : 'hidden-default'?>"><span class="fa fa-pencil"></span></button></div>
		<div class="panel-body">
		
			<div id="page-edit-session-properties" class="hidden-default">
				<form id="update-session-form-page" 

					  action="/api/v1/sessions/<?=$session->id?>" 
					  method="put"
					  data-alert-success="<?=__('session.alert.success.update_session')?>"
					  data-alert-error="<?=__('session.alert.error.update_session')?>"
				>
					<div class="form-group ">
						<textarea id="session-notes" name="notes" class="form-control" rows="1" placeholder="<?=__('session.field.notes')?>"></textarea>
					</div>

					<div class="form-inline">
						<span id="page-edit-session-deadline">
							<label for="deadline"><?=__('session.field.deadline')?></label>
							<input id="session-deadline" class="timepicker form-control" name="deadline" type="text" id="deadline" maxlength="5" size="5" required/>
						</span>

						<span id="page-edit-session-cost">
							<label for="cost"><?=__('product.field.cost')?></label>
							<div class="input-group">
								<div class="input-group-addon">€</div>
								<input id="session-cost" style="z-index: 0;" name="cost" class="form-control" type="number" step="0.01" max="100" min="0" required/>	
							</div>
						</span>
						<br>
					</div>

					<div class="form-group">
						<label><?=__('product.field.paid_by')?></label>
						<select class="form-control" id="session-payer" name="payer_id">
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
			</div>
			
			<div id="session-properties">
				<div id="s" class="well">
					<?=$session->notes?>
				</div>
				<dl>
					<dt><?=__('session.field.deadline')?></dt>
					<dd id="static-session-deadline"></dd>
					<dt><?=__('product.field.cost')?></dt>
					<dd>€ <span id="static-session-cost"></span></dd>
					<dt><?=__('product.field.paid_by')?></dt>
					<dd id="static-session-payer"></dd>
				</dl>
			</div>	
		</div>
	</div>