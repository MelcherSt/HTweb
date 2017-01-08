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
					

					foreach($menu_items as $item) {
						$section_segment = $item[0];
						$section_title = $item[1];
						$section_icon = $item[2];

						?>
						<li class="<?php echo $active_item == $section_segment ? 'active' : '' ?>">
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
						<li><a href="/users/me"><span class="fa fa-user-circle"></span> <?=__('me')?></a></li>
						<li><a href="/users/edit"><span class="fa fa-cogs"></span> <?=__('settings')?></a></li>
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