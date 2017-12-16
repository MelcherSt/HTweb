<!-- SIDENAV -->
<div class="col-md-4">

	<!-- Basic session navigation -->
	<div class="card mb-4">
		<div class="card-header"><?= __('actions.name') ?></div>
		<div class="list-group">
			<a class="list-group-item" href="/sessions/yesterday"><i class="fa fa-chevron-left" aria-hidden="true"></i> <?= __('session.day.yesterday') ?></a>
			<a class="list-group-item" href="/sessions/today"><i class="fa fa-cutlery" aria-hidden="true"></i> <?= __('session.day.today') ?></a>
			<a class="list-group-item" href="/sessions/tomorrow"><i class="fa fa-chevron-right" aria-hidden="true"></i> <?= __('session.day.tomorrow') ?></a>
		</div>
		<div class="list-group">
			<?php if (\Auth::has_access('sessions.administration')) { ?>
				<a href="/sessions/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> <?= __('privileges.perm.manage') ?></a>
			<?php } ?>
		</div>
	</div>

	<!-- Quick enrollment form -->
	<div class="card">
		<div class="card-header">
			<?= __('session.index.quick_enroll') ?>
		</div>

		<div class="card-body">
			<?= Presenter::forge('quickenroll') ?>
		</div>
	</div>	
</div>

<!-- BODY -->
<div class="col-md-8">
	<p><?= __('session.index.msg') ?> <a href="/receipts"><?= __('receipt.title') ?></a>.</p>
	<?= Presenter::forge('overview') ?>
</div>





