<div class="col-lg-3 col-md-6">
	<div class="panel <?=$style?>">
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-3">
					<i class="fa <?=$icon?> fa-5x"></i>
				</div>
				<div class="col-xs-9 text-right">
					<div class="huge"><?=$count?> <small><?=$kind?></small></div>
					<div><?=$message?></div>
				</div>
			</div>
		</div>	
		<?php if ($details) : ?>
		<a href="<?=$link?>">
			<div class="panel-footer">
				<span class="pull-left"><?=$detail?></span>
				<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
				<div class="clearfix"></div>
			</div>
		</a>
		<?php endif; ?>
	</div>
</div>