<li class="dropdown">
	<a data-toggle="dropdown" class="dropdown-toggle" href="#">
		<span class="fa fa-bell"><sup><span id="notification-count-badge" class="badge" style="color: white; background-color: #A52A2A;"><?= $unread_count ?></span></sup></span></b>
	</a>
	<ul class="dropdown-menu notification-menu">
		<?php foreach ($notifications as $notification) : ?>
		<li><a href="<?= $notification->href ?>"><span class="<?= $notification->icon ?>"></span> <<?= $notification->text ?></a></li>
		<?php endforeach; ?>
		<?php if (sizeof($notifications) == 0) : ?>
		<li><a href="#"><em><?= __('notifications.empty_list') ?></em></a></li>
		<?php endif; ?>
		<li role="separator" class="divider"></li>
		<li><a href="<?=Uri::create('notifications')?>"><?=__('notifications.all_notifications')?></a></li>
	</ul>
</li>