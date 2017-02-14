<?php echo Form::open(array("class"=>"", "enctype"=>"multipart/form-data")); ?>

	<fieldset>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
				<?php echo Form::label('Username', 'username', array('class'=>'control-label')); ?>

						<?php echo Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Username')); ?>

				</div>		
				<div class="form-group">
					<?php echo Form::label('Name', 'name', array('class'=>'control-label')); ?>

						<?php echo Form::input('name', Input::post('name', isset($user) ? $user->name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Name')); ?>

				</div>
				<div class="form-group">
					<?php echo Form::label('Surname', 'surname', array('class'=>'control-label')); ?>
					<?php echo Form::input('surname', Input::post('surname', isset($user) ? $user->surname : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Surname')); ?>
				</div>
				<div class="form-group">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="active" <?=$user->active ? 'checked' : ''?>> Active
						</label>
					</div>
				</div>
				<div class="form-group">
					<?php echo Form::label('Start year', 'start_year', array('class'=>'control-label')); ?>
					<?php echo Form::input('start_year', Input::post('start_year', isset($user) ? $user->start_year : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Start year')); ?>
				</div>
				<div class="form-group">
					<?php echo Form::label('End year', 'end_year', array('class'=>'control-label')); ?>
					<?php echo Form::input('end_year', Input::post('end_year', isset($user) ? $user->end_year : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'End year')); ?>
				</div>
				<div class="form-group">
					<?php echo Form::label('Point count', 'points', array('class'=>'control-label')); ?>
					<?php echo Form::input('points', Input::post('points', isset($user) ? $user->points : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Point count')); ?>
				</div>
				
				<!--
				<div class="form-group">
					<label for="avatar_upload"><?=__('user.field.img')?></label>
					<?php echo Form::file('avatar_upload', array('class' => 'col-md-4 form-control', 'placeholder'=>'123')); ?>	
				</div>
				-->
				<div class="form-group">
					<label for="lang"><?=__('user.field.lang')?></label>
					<select class="form-control" id="lang" name="lang">
						<option value="nl" <?=Config::get('language') == 'nl' ? 'selected' : ''?>><?=__('user.language.nl')?></option>
						<option value="en" <?=Config::get('language') == 'en' ? 'selected' : ''?>><?=__('user.language.en')?></option>
					</select>
				</div>

			</div>

			<div class="col-md-6">
				<div class="form-group">
					<?php echo Form::label('Group', 'group_id', array('class'=>'control-label')); ?>
					<select class="form-control" name="group_id">
						<option value="<?=$user->group_id?>"><?=Auth::group()->get_name($user->group_id)?></option>
						<?php foreach(Auth::groups() as $group) { ?>
						<option value="<?=$group->id?>"><?=$group->name?></option>
						<?php } ?>
						
					</select>
				
				</div>
				<div class="form-group">
					<?php echo Form::label(__('user.field.iban.'), 'iban', array('class'=>'control-label')); ?>
					<?php echo Form::input('iban', Input::post('iban', isset($user) ? $user->iban : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'IBAN')); ?>
				</div>
				<div class="form-group">
					<?php echo Form::label(__('user.field.email'), 'email', array('class'=>'control-label')); ?>
					<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Email')); ?>
				</div>
				<div class="form-group">
					<?php echo Form::label(__('user.field.phone'), 'phone', array('class'=>'control-label')); ?>
					<?php echo Form::input('phone', Input::post('phone', isset($user) ? $user->phone : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Phone')); ?>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" type="submit" ><span class="fa fa-floppy-o"></span> <?=__('user.edit.btn')?></button>	
				</div>
			</div>
		</div>
	</fieldset>
<?php echo Form::close();