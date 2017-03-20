<div class="row">
	<p><?=__('gate.login.msg')?></p>
	<div class="col-md-3">
		<?=Form::open()?>
			<?php if (Input::get('destination') !== null): ?>
				<?=Form::hidden('destination', Input::get('destination')); ?>
			<?php endif; ?>

			<?php if (isset($login_error)): ?>
				<div class="error"><?=$login_error; ?></div>
			<?php endif; ?>

			<div class="form-group <?=! $val->error('email') ?: 'has-error' ?>">
				<?=Form::label(__('gate.login.label.username'), 'email')?>
				<?=Form::input('email', Input::post('email'), ['class' => 'form-control', 'placeholder' => __('gate.login.label.username'), 'autofocus'])?>

				<?php if ($val->error('email')): ?>
					<span class="control-label"><?=$val->error('email')->get_message('You must provide a username or email')?></span>
				<?php endif; ?>
			</div>

			<div class="form-group <?=! $val->error('password') ?: 'has-error' ?>">
				<?=Form::label(__('gate.login.label.pass'), 'password')?>
				<?=Form::password('password', null, ['class' => 'form-control', 'placeholder' => __('gate.login.label.pass')])?>

				<?php if ($val->error('password')): ?>
					<span class="control-label"><?=$val->error('password')->get_message(':label cannot be blank'); ?></span>
				<?php endif; ?>
			</div>
				
			<div class="from-group">
				<div class="checkbox">
					<label>
					<?=Form::checkbox('rememberme', 'on')?>	
					<?=__('gate.login.label.remember_me')?>	
					</label>
				</div>
			</div>
				
			<div class="form-group">
				<a href="/gate/reset"><u><?=__('gate.login.reset')?></u></a>
			</div>		

			<div class="actions">
				<?=Form::submit(['value'=> __('gate.login.btn'), 'name'=>'submit', 'class' => 'btn btn-lg btn-primary btn-block'])?>
			</div>
		<?=Form::close()?>
	</div>
</div>
