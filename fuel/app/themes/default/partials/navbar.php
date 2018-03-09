<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/"><span class="fa fa-bank"></span> <?=__('site_title')?> <small><?=__('site_sub')?></small>  
				<sup><span class="badge"><?=__('state')?></span></sup>
			</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?= MenuFactory::forge($menu_root_name) ?> 
			</ul>
			<ul class="nav navbar-nav pull-right">
				<?php if (!\Context_Base::_is_guest()){ ?>
				<?= Request::forge('notifications/dropdown')->execute() ?>
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><span class="fa fa-user"></span> <?= $current_user->name ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="/users/edit"><span class="fa fa-cogs"></span> <?=__('settings')?></a></li>
						<li><a href="/privileges"><span class="fa fa-shield"></span> <?=__('privileges.title')?></a></li>
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