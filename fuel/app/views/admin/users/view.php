<h2>Viewing #<?php echo $enrollment->id; ?></h2>

<p>
	<strong>Username:</strong>
	<?php echo $enrollment->username; ?></p>
<p>
	<strong>Surname:</strong>
	<?php echo $enrollment->surname; ?></p>
<p>
	<strong>Name:</strong>
	<?php echo $enrollment->name; ?></p>
<p>
	<strong>Phone:</strong>
	<?php echo $enrollment->phone; ?></p>
<p>
	<strong>Active:</strong>
	<?php echo $enrollment->active; ?></p>
<p>
	<strong>Start year:</strong>
	<?php echo $enrollment->start_year; ?></p>
<p>
	<strong>End year:</strong>
	<?php echo $enrollment->end_year; ?></p>
<p>
	<strong>Point count:</strong>
	<?php echo $enrollment->points; ?></p>
<p>
	<strong>Balance:</strong>
	<?php echo $enrollment->balance; ?></p>
<p>
	<strong>Password:</strong>
	<?php echo $enrollment->password; ?></p>
<p>
	<strong>Group:</strong>
	<?php echo $enrollment->group_id; ?></p>
<p>
	<strong>Email:</strong>
	<?php echo $enrollment->email; ?></p>

<?php echo Html::anchor('admin/users/edit/'.$enrollment->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/users', 'Back'); ?>