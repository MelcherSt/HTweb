<?php echo render('users/admin/_form'); ?>
<p>
	<?php echo Html::anchor('users/admin/view/'.$user->id, 'View'); ?> |
	<?php echo Html::anchor('users/admin', 'Back'); ?></p>
