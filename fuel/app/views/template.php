<!DOCTYPE html>
<html>
<head>	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=$title?> | <?=__('site_title') . ' ' . __('site_sub')?></title>
	
	<?php 
	echo Asset::css(array(
		'bootstrap.min.css',
		'font-awesome.css',
		'styles.css',
		'jquery.timepicker-1.3.5.min.css'
		)); 
	
	echo Asset::js(array(
		'jquery-3.1.1.min.js',
		'bootstrap.min.js',
		'scripts.js',
		'jquery.timepicker-1.3.5.min.js',	
	)); 
	?>
		
</head>
<body>
	
	<?= Request::forge('/nav/base')->execute([Uri::segment(1)]); ?>
	
	<div class="container">
		<div class="row">
			<div class="row">
				<h1><?php if(isset($page_title)) { echo $page_title; }?> <small><?php if(isset($subtitle)) { echo $subtitle; } ?></small></h1>
				<hr>
				<?php if (Session::get_flash('success')): ?>
				<div class="alert alert-success alert-dismissable fade" id="alert-success" data-dismiss="alert">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
					<p>
					<?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
					</p>
				</div>
				<?php endif; ?>
				<?php if (Session::get_flash('error')): ?>
					<div class="alert alert-danger alert-dismissable fade" id="alert-error" data-dismiss="alert"> 
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
						<p>
						<?php echo implode('</p><p>', (array) Session::get_flash('error')); ?>
						</p>
					</div>
				<?php endif; ?>
			</div>
			
			<?=$content?>
		</div>
		<hr/>
		<footer>
			<p><span class="fa fa-bank"></span> <?=__('site_sub')?> <small>© 2016-2017</small><br>
				<small><?= __('fuel') . e(Fuel::VERSION); ?></small>
			</p>
			<p class="pull-right"><?=__('render')?></p>
		</footer>
	</div>
</body>
</html>
