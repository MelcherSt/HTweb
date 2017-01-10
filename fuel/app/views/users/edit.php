<?php echo Form::open(array("class"=>"form-horizontal", "enctype"=>"multipart/form-data")); ?>

	<fieldset>
		<div class="col-md-3">
			<div class="form-group">
				<?php echo Form::label(__('user.field.iban'), 'iban', array('class'=>'control-label')); ?>
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

		<div class="col-md-3 col-md-offset-3">
			<div class="form-group">
				<?php echo Form::label(__('user.edit.label'), 'password', array('class'=>'control-label')); ?>
				<?php echo Form::password('old_password', Input::post('old_password'), array('class' => 'col-md-4 form-control', 'placeholder'=>__('user.edit.placeholder.current_pass'))); ?>
				<?php echo Form::password('password', Input::post('password'), array('class' => 'col-md-4 form-control', 'placeholder'=>__('user.edit.placeholder.new_pass'))); ?>
				<?php echo Form::password('password2', Input::post('password2'), array('class' => 'col-md-4 form-control', 'placeholder'=>__('user.edit.placeholder.re_pass'))); ?>
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit" ><span class="fa fa-floppy-o"></span> <?=__('user.edit.btn')?></button>	
			</div>
		</div>
	</fieldset>
<?php echo Form::close();