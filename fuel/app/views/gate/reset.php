<div class="row">
	<p><?=__('gate.reset.msg')?></p>
	<div class="col-md-3">
		<?php echo Form::open(array()); ?>

			<div class="form-group">
				<label for="email"><?=__('gate.reset.label.mail')?></label>
				<?php echo Form::input('email', '', array('class' => 'form-control', 'placeholder' => __('gate.reset.label.mail'), 'autofocus')); ?>
			</div>
			<div class="actions">
				<?php echo Form::submit(array('value'=> __('gate.reset.btn'), 'name'=>'submit', 'class' => 'btn btn-primary btn-block')); ?>
			</div>

		<?php echo Form::close(); ?>
	</div>
</div>
