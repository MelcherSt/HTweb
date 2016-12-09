<h2>Viewing #<?php echo $user->id; ?></h2>

<p>
	<strong>Username:</strong>
	<?php echo $user->username; ?></p>
<p>
	<strong>Surname:</strong>
	<?php echo $user->surname; ?></p>
<p>
	<strong>Name:</strong>
	<?php echo $user->name; ?></p>
<p>
	<strong>Phone:</strong>
	<?php echo $user->phone; ?></p>
<p>
	<strong>Active:</strong>
	<?php echo $user->active; ?></p>
<p>
	<strong>Start year:</strong>
	<?php echo $user->start_year; ?></p>
<p>
	<strong>End year:</strong>
	<?php echo $user->end_year; ?></p>
<p>
	<strong>Point count:</strong>
	<?php echo $user->points; ?></p>
<p>
	<strong>Balance:</strong>
	<?php echo $user->balance; ?></p>
<p>
	<strong>Password:</strong>
	<?php echo $user->password; ?></p>
<p>
	<strong>Group:</strong>
	<?php echo $user->group_id; ?></p>
<p>
	<strong>Email:</strong>
	<?php echo $user->email; ?></p>

<?php echo Html::anchor('admin/users/edit/'.$user->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/users', 'Back'); ?>