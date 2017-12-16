<?php \Lang::load('session'); ?>

<div class="card <?= $style ?>">
	<div class="card-header">
		<span class="fa <?= $icon ?>"></span>
	</div>
	<div class="card-body">
		<h4 class="card-title"><?= $count ?> <small><?= $kind ?></small></h4>
		<p class="card-text"><?= $message ?></p>

	</div>

	<div class="card-footer">
		<?php if ($details) : ?>
			<a href="<?= $link ?>" class="btn btn-primary">
				<?= $detail ?>		
			</a>
		<?php endif; ?>
	</div>
</div>