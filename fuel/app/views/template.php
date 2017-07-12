<!DOCTYPE html>
<html>
<head>	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($page_title) && empty($title) ? $page_title : $title?> | <?=__('site_title') . ' ' . __('site_sub')?></title>
	
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
	<?=$navigation?>
	<div class="container">
		<div class="row">
			<?=$header?>
		</div>
		<div class="row">
			<?=$content?>
		</div>
		<div class="row">
			<?=$footer?>		
		</div>
	</div>
</body>
</html>
