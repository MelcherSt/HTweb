<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Title', 'title', array('class'=>'control-label')); ?>

				<?php echo Form::input('title', Input::post('title', isset($product) ? $product->title : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Title')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Notes', 'notes', array('class'=>'control-label')); ?>

				<?php echo Form::textarea('notes', Input::post('notes', isset($product) ? $product->notes : ''), array('class' => 'col-md-8 form-control', 'rows' => 8, 'placeholder'=>'Notes')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Price', 'price', array('class'=>'control-label')); ?>

				<?php echo Form::input('price', Input::post('price', isset($product) ? $product->price : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Price')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Paid by', 'paid_by', array('class'=>'control-label')); ?>

				<?php echo Form::input('paid_by', Input::post('paid_by', isset($product) ? $product->paid_by : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Paid by')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Settled', 'settled', array('class'=>'control-label')); ?>

				<?php echo Form::input('settled', Input::post('settled', isset($product) ? $product->settled : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Settled')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>