<?php
foreach(Auth::groups() as $group) { 
	$group_options[$group->id] = $group->name;
} 
?>

<?=Form::open(array("class"=>"", "enctype"=>"multipart/form-data"))?>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
			<?=Form::label('Username', 'username', array('class'=>'control-label'))?>
			<?=Form::input('username', Input::post('username', isset($user) ? $user->username : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Username'))?>

			</div>		
			<div class="form-group">
				<?=Form::label('Name', 'name', array('class'=>'control-label'))?>
				<?=Form::input('name', Input::post('name', isset($user) ? $user->name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Name'))?>
			</div>
			<div class="form-group">
				<?=Form::label('Surname', 'surname', array('class'=>'control-label'))?>
				<?=Form::input('surname', Input::post('surname', isset($user) ? $user->surname : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Surname'))?>
			</div>
			<div class="form-group">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="active" checked> Active
					</label>
				</div>
			</div>
			<div class="form-group">
				<?=Form::label('Start year', 'start_year', array('class'=>'control-label'))?>
				<?=Form::input('start_year', Input::post('start_year', isset($user) ? $user->start_year : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Start year'))?>
			</div>
			<div class="form-group">
				<?=Form::label('End year', 'end_year', array('class'=>'control-label'))?>
				<?=Form::input('end_year', Input::post('end_year', isset($user) ? $user->end_year : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'End year'))?>
			</div>
			<div class="form-group">
				<?=Form::label('Point count', 'points', array('class'=>'control-label'))?>
				<?=Form::input('points', Input::post('points', isset($user) ? $user->points : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Point count'))?>
			</div>

			<div class="form-group">
				<?=Form::label(__('user.field.lang'), 'lang')?>
				<?=Form::select('lang', 0, ['nl' => __('user.language.nl'), 'en' => __('user.language.en')], ['class' => 'form-control'])?>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<?=Form::label('Group', 'group_id', array('class'=>'control-label'))?>
				<?=Form::select('group-id', 3, $group_options, ['class' => 'form-control'])?>
			</div>
			<div class="form-group">
				<?=Form::label(__('user.field.iban.'), 'iban', array('class'=>'control-label'))?>
				<?=Form::input('iban', Input::post('iban', isset($user) ? $user->iban : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'IBAN'))?>
			</div>
			<div class="form-group">
				<?=Form::label(__('user.field.email'), 'email', array('class'=>'control-label'))?>
				<?=Form::input('email', Input::post('email', isset($user) ? $user->email : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Email'))?>
			</div>
			<div class="form-group">
				<?=Form::label(__('user.field.phone'), 'phone', array('class'=>'control-label'))?>
				<?=Form::input('phone', Input::post('phone', isset($user) ? $user->phone : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Phone'))?>
			</div>
			<div class="form-group">
				<?=Form::label(__('user.edit.label'), 'password', array('class'=>'control-label'))?>
				<?=Form::password('password', Input::post('password'), array('class' => 'col-md-4 form-control', 'placeholder'=>__('user.edit.placeholder.new_pass')))?>
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit" ><span class="fa fa-floppy-o"></span> <?=__('user.edit.btn')?></button>	
			</div>
		</div>
	</div>
<?=Form::close();