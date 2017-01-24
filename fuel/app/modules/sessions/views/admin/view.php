<?php 

$deadline = date('H:i', strtotime($session->deadline)); 

?>


<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/sessions/view/<?=$session->date?>"><i class="fa fa-eye" aria-hidden="true"></i> View original</a>
				<a class="list-group-item" href="#"><i class="fa fa-trash" aria-hidden="true"></i> <?=__('actions.remove')?></a>
			</div>
		</div>
		
		<?php if($session->settled) {?>
		<div class="panel panel-default">
			<div class="panel-heading">Properties</div>
			<div class="panel-body">
				<p>You cannot edit this session since it has been settled.</p>	
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
			</div>
		</div>
		<?php } else { ?>	
		<div class="panel panel-default">
			<div class="panel-heading">Properties</div>
			
			<div class="panel-body">
				<form method="post" action="/sessions/admin/update">
					
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
							<input name="cost" class="form-control" type="number" step="0.01" max="100" min="0" value="<?=$session->cost?>"required/>	
						</div>
						<br>
						<label><?=__('product.field.paid_by')?></label>
						<select class="form-control" id="add-user-id" name="payer_id">
							<option value="<?=$session->paid_by?>"><?=$session->get_payer()->get_fullname()?></option>

							<?php foreach($session->enrollments as $enrollment):
									$user_id = $enrollment->user->id;
									if($user_id == $session->paid_by) { continue; }
							?>
							<option value="<?=$user_id?>"><?=$enrollment->user->get_fullname()?></option>
							<?php endforeach;  ?>
						</select>	
					</div>

					<button class="btn btn-sm btn-primary" type="submit" ><span class="fa fa-pencil-square-o"></span> <?=__('session.view.btn.update_session')?></button>

				</form>
			</div>
		</div>
		<?php } ?>
	</div>
	
	<div class="col-md-4">
		
	</div>
</div>