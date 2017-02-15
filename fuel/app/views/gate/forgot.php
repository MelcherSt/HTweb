<div class="row">
	<p>Enter your email address to send a password reset link.</p>
	<div class="col-md-3">
		<?php echo Form::open(array()); ?>

			<div class="form-group">
				<label for="email">Email:</label>
				<?php echo Form::input('email', Input::post('email'), array('class' => 'form-control', 'placeholder' => 'Email or Username', 'autofocus')); ?>
			</div>
			<div class="actions">
				<?php echo Form::submit(array('value'=>'Send reset link', 'name'=>'submit', 'class' => 'btn btn-primary btn-block')); ?>
			</div>

		<?php echo Form::close(); ?>
	</div>
</div>
