
<div class="list-group">
<?php foreach ($notifications as $notification) : ?>
	
		<a href="<?= Uri::create($notification->href) ?>" class="list-group-item">
			<h4 class="list-group-item-heading">
				<span class="<?= $notification->icon ?>"></span>
				<?= $notification->title ?>
				<small class="pull-right"><?= Utils::format_date($notification->date_time, Utils::DATETIME_FORMAT)?></small>
			</h4>
			<p class="list-group-item-text"><?= $notification->text ?></p>
		</a>
	
<?php endforeach; ?>
<?php if (sizeof($notifications) == 0) : ?>
	<em><?= __('notifications.empty_list') ?></em>
<?php endif; ?>
</div>