<div class="row">
	<h1><?=isset($page_title) ? $page_title : ''?> <small><?=isset($sub_title) ? $sub_title : ''?></small></h1>		 
	<hr>
	<div class="alert alert-success alert-dismissable hidden-default" id="alert-success" data-dismiss="alert">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
		<p id="alert-success-msg">
		<?=implode('</p><p>', (array) Session::get_flash('success'))?>
		</p>
	</div>

	<div class="alert alert-danger alert-dismissable hidden-default" id="alert-error" data-dismiss="alert"> 
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
		<p id="alert-error-msg">
		<?=implode('</p><p>', (array) Session::get_flash('error'))?>
		</p>
	</div>
</div>