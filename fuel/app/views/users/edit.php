<?php echo Form::open(array("class"=>"form-horizontal", "enctype"=>"multipart/form-data")); ?>

	<fieldset>
		<div class="col-md-3">
			<div class="form-group">
				<?php echo Form::label('IBAN', 'iban', array('class'=>'control-label')); ?>
				<?php echo Form::input('iban', Input::post('iban', isset($user) ? $user->iban : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'IBAN')); ?>
			</div>
			<div class="form-group">
				<?php echo Form::label('Email', 'email', array('class'=>'control-label')); ?>
				<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Email')); ?>
			</div>
			<div class="form-group">
				<?php echo Form::label('Phone', 'phone', array('class'=>'control-label')); ?>
				<?php echo Form::input('phone', Input::post('phone', isset($user) ? $user->phone : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Phone')); ?>
			</div>
			<div class="form-group">	
				<?php echo Form::label('Avatar', 'avatar_upload', array('class'=>'control-label')); ?>
				<?php echo Form::file('avatar_upload', array('class' => 'col-md-4 form-control', 'placeholder'=>'123')); ?>
				
			</div>
		</div>
		<div class="col-md-3 col-md-offset-3">
			<div class="form-group">
				<?php echo Form::label('Change password', 'password', array('class'=>'control-label')); ?>
				<?php echo Form::password('old_password', Input::post('old_password'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Current password')); ?>
				<?php echo Form::password('password', Input::post('password'), array('class' => 'col-md-4 form-control', 'placeholder'=>'New password')); ?>
				<?php echo Form::password('password2', Input::post('password2'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Re-type password')); ?>
			</div>
			<div class="form-group">
				<label class='control-label'>&nbsp;</label>
				<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		
			</div>
		</div>
	</fieldset>
<?php echo Form::close();