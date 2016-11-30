<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css('bootstrap.css'); ?>
	<?php echo Asset::css('font-awesome.css'); ?>
	
	<style>
		body { margin: 60px; }
	</style>
	<?php echo Asset::js(array(
		'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
		'bootstrap.js',
	)); ?>
	<script>
		$(function(){ $('.topbar').dropdown(); });
	</script>
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
				<a class="navbar-brand" href="#"><span class="fa fa-bank"></span> Het Tribunaal <small>Web</small></a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="<?php echo Uri::segment(2) == '' ? 'active' : '' ?>">
						<?php echo Html::anchor('', 'Dashboard') ?>
					</li>

					<?php					
					/*
						$files = new GlobIterator(APPPATH.'classes/controller/admin/*.php');
						foreach($files as $file)
						{
							$section_segment = $file->getBasename('.php');
							$section_title = Inflector::humanize($section_segment);
							?>
							<li class="<?php echo Uri::segment(2) == $section_segment ? 'active' : '' ?>">
								<?php echo Html::anchor('admin/'.$section_segment, $section_title) ?>
							</li>
							<?php
						}*/
					?>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<?php if (isset($current_user)){ ?>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="fa fa-user"></span> <?php echo $current_user->username ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo Html::anchor('gate/logout', 'Logout') ?></li>
						</ul>
					</li>
					<?php } else { ?>
					<li><?php echo Html::anchor('gate/login', 'Login'); ?></li>
					<?php } ?>
				</ul>
	
			</div>
		</div>
	</div>
	

	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1><?php echo $title; ?></h1>
				<hr>
				<?php if (Session::get_flash('success')): ?>
				<div class="alert alert-success alert-dismissable fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" aria-label="Close">&times;</button>
					<p>
					<?php echo implode('</p><p>', (array) Session::get_flash('success')); ?>
					</p>
				</div>
				<?php endif; ?>
				<?php if (Session::get_flash('error')): ?>
					<div class="alert alert-danger alert-dismissable fade in">
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
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
			<p>HTWeb on 
				<?php echo Request::forge('devtool/branch')->execute(); ?><br>
				<small>using FUELPhp Version: <?php echo e(Fuel::VERSION); ?></small>
			</p>
		</footer>
	</div>
</body>
</html>
