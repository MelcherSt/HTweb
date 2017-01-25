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
		)); 
	
	foreach ($add_css as $sheet) { 
		echo Asset::css($sheet . '.css');
	} 
	
	echo Asset::js(array(
		'jquery-3.1.1.min.js',
		'bootstrap.min.js',
		'scripts.js',	
	)); 
	
	foreach ($add_js as $script) { 
		echo Asset::js($script . '.js');
	} 
	
	?>
		
</head>
<body>
	
	<?= Request::forge('/nav/base')->execute([Uri::segment(1)]); ?>
	
	<div class="container">
		<div class="row">
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
