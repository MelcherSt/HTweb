<div class="navbar navbar-expand-md navbar-dark bg-dark fixed-top" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="/"><span class="fa fa-bank"></span> <?= __('site_title') ?> <small><?= __('site_sub') ?></small>  
			<sup><span class="badge"><?= __('state') ?></span></sup>
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button

		<div class="navbar-collapse collapse">
			<ul class="navbar-nav mr-auto">
				<?= MenuFactory::forge($menu_root_name) ?> 
			</ul>
			<ul class="navbar-nav mr-auto pull-right">
				<?php if (!\Context_Base::_is_guest()) { ?>
					<li class="dropdown">
						<a class="nav-link dropdown-toggle" id="user-dropdown" data-toggle="dropdown"  href="#"><span class="fa fa-user"></span> <?= $current_user->name.' '.$current_user->surname ?> <b class="caret"></b></a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="/users/edit"><span class="fa fa-cogs"></span> <?= __('settings') ?></a>
							<a class="dropdown-item" href="/privileges"><span class="fa fa-shield"></span> <?= __('privileges.title') ?></a>
							<a class="dropdown-item" href="/gate/logout"><span class="fa fa-sign-out"></span> <?= __('logout') ?></a>
						</div>
					</li>
				<?php } else { ?>
					<li><a href="/gate/login"><span class="fa fa-sign-in"></span> <?= __('login') ?></a></li>
					<?php } ?>
			</ul>

		</div>
	</div>
</div>