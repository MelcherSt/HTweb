<div class="row">
	<p><?=__('gate.login.msg')?></p>
	<div class="col-md-3">
		<?php echo Form::open(array()); ?>

			<?php if (Input::get('destination') !== null): ?>
				<?php echo Form::hidden('destination', Input::get('destination')); ?>
			<?php endif; ?>

			<?php if (isset($login_error)): ?>
				<div class="error"><?php echo $login_error; ?></div>
			<?php endif; ?>

			<div class="form-group <?php echo ! $val->error('email') ?: 'has-error' ?>">
				<label for="email"><?=__('gate.login.label.username')?></label>
				<?php echo Form::input('email', Input::post('email'), array('class' => 'form-control', 'placeholder' => __('gate.login.label.username'), 'autofocus')); ?>

				<?php if ($val->error('email')): ?>
					<span class="control-label"><?php echo $val->error('email')->get_message('You must provide a username or email'); ?></span>
				<?php endif; ?>
			</div>

			<div class="form-group <?php echo ! $val->error('password') ?: 'has-error' ?>">
				<label for="password"><?=__('gate.login.label.pass')?></label>
				<?php echo Form::password('password', null, array('class' => 'form-control', 'placeholder' => __('gate.login.label.pass'))); ?>

				<?php if ($val->error('password')): ?>
					<span class="control-label"><?php echo $val->error('password')->get_message(':label cannot be blank'); ?></span>
				<?php endif; ?>
			</div>
				
			<div class="from-group">
				<div class="checkbox">
				<label>
				<input name="rememberme" type="checkbox">
				<?=__('gate.login.label.remember_me')?>
				</label>
				</div>
			</div>
				
			<div class="form-group">
				<a href="/gate/reset"><u><?=__('gate.login.reset')?></u></a>
			</div>		

			<div class="actions">
				<?php echo Form::submit(array('value'=> __('gate.login.btn'), 'name'=>'submit', 'class' => 'btn btn-lg btn-primary btn-block')); ?>
			</div>

		<?php echo Form::close(); ?>
	</div>
</div>
