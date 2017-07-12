<div class="row">
	<h1><?php if(isset($page_title)) { echo $page_title; }?> <small><?php if(isset($subtitle)) { echo $subtitle; } ?></small></h1>		 
	<hr>
	<div class="alert alert-success alert-dismissable hidden-default" id="alert-success" data-dismiss="alert">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
		<p id="alert-success-msg">
		<?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
		</p>
	</div>

	<div class="alert alert-danger alert-dismissable hidden-default" id="alert-error" data-dismiss="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
		<p id="alert-error-msg">
		<?php echo implode('</p><p>', (array) Session::get_flash('error')); ?>
		</p>
	</div>
</div>