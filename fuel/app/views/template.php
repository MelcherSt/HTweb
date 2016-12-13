<!DOCTYPE html>
<html>
<head>
	
	<?php 
	Lang::load('template'); 
	Lang::load('session', 'session');
	Lang::load('user', 'user');
	Lang::load('actions', 'actions');	
	Lang::load('alert', 'alert');
	?>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
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
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/"><span class="fa fa-bank"></span> <?=__('site_title')?> <small><?=__('site_sub')?></small></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<?php					
						if (isset($current_user)) {
							$menu_items = array(
								array('sessions', 'My Sessions', 'fa-cutlery'), 
								array('receipts', 'My Receipts', 'fa-money'),
								array('stats', 'My stats', 'fa-line-chart'),
								array('wall', 'The wall', 'fa-id-card'),
								array('content/posts', 'Posts', 'fa-file-text'),
							);
						} else {
							$menu_items = array();
						}
						
						foreach($menu_items as $item) {
							$section_segment = $item[0];
							$section_title = $item[1];
							$section_icon = $item[2];
							
							?>
							<li class="<?php echo Uri::segment(1) == $section_segment ? 'active' : '' ?>">
								<a href="/<?=$section_segment?>"><span class="fa <?=$section_icon?>"></span> <?=$section_title?> </a>
							</li>
							<?php
						}
					?>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<?php if (isset($current_user)){ ?>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="fa fa-user"></span> <?php echo $current_user->name . ' ' . $current_user->surname; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="/users/me"><span class="fa fa-cogs"></span> <?=__('settings')?></a></li>
							<li><a href="/gate/logout"><span class="fa fa-sign-out"></span> <?=__('logout')?></a></li>
						</ul>
					</li>
					<?php } else { ?>
					<li><a href="/gate/login"><span class="fa fa-sign-in"></span> <?=__('login')?></a></li>
					<?php } ?>
				</ul>
	
			</div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
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
			<div class="col-md-12">				
				<?php echo $content; ?>
			</div>
		</div>
		<hr/>
		<footer>
			<p><span class="fa fa-bank"></span> <?=__('site_sub')?> <small>© 2016</small><br>
				<small>Build using FuelPHP <?php echo e(Fuel::VERSION); ?></small>
			</p>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
		</footer>
	</div>
</body>
</html>
