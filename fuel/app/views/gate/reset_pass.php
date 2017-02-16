<div class="row">
	<p>Enter your new password.</p>
	<div class="col-md-3">
		<?php echo Form::open(array('action' => 'gate/reset/pass')); ?>

			<div class="form-group">
				<?php echo Form::hidden('token', $token); ?>
				<?php echo Form::input('email', '', array('class' => 'form-control', 'placeholder' => __('gate.reset.label.mail'), 'autofocus')); ?>
				<?php echo Form::password('password', '', array('class' => 'col-md-4 form-control', 'placeholder'=>__('user.edit.placeholder.new_pass'))); ?>
				<?php echo Form::password('password2', '', array('class' => 'col-md-4 form-control', 'placeholder'=>__('user.edit.placeholder.re_pass'))); ?>
			</div>
			<div class="form-group">
				<button class="btn btn-primary" type="submit" ><span class="fa fa-floppy-o"></span> <?=__('user.edit.btn')?></button>	
			</div>

		<?php echo Form::close(); ?>
	</div>
</div>
