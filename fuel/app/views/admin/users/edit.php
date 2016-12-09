<h2>Editing User</h2>
<br>

<?php echo render('admin/users/_form'); ?>
<p>
	<?php echo Html::anchor('admin/users/view/'.$enrollment->id, 'View'); ?> |
	<?php echo Html::anchor('admin/users', 'Back'); ?></p>
