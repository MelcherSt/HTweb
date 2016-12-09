<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
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
			<?php echo Form::label('Phone', 'phone', array('class'=>'control-label')); ?>

				<?php echo Form::input('phone', Input::post('phone', isset($user) ? $user->phone : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Phone')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Active', 'active', array('class'=>'control-label')); ?>

				<?php echo Form::input('active', Input::post('active', isset($user) ? $user->active : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Active')); ?>

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
		<div class="form-group">
			<?php echo Form::label('Balance', 'balance', array('class'=>'control-label')); ?>

				<?php echo Form::input('balance', Input::post('balance', isset($user) ? $user->balance : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Balance')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Password', 'password', array('class'=>'control-label')); ?>

				<?php echo Form::input('password', Input::post('password', isset($user) ? $user->password : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Password')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Group', 'group_id', array('class'=>'control-label')); ?>

				<?php echo Form::input('group_id', Input::post('group_id', isset($user) ? $user->group_id : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Group')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Email', 'email', array('class'=>'control-label')); ?>

				<?php echo Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Email')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>