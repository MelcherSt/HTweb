<p>
	<strong>Username:</strong>
	<?php echo $user->username; ?></p>
<p>
	<strong>Full name:</strong>
	<?php echo $user->get_fullname(); ?></p>
<p>
	<strong>Phone:</strong>
	<?php echo $user->phone; ?></p>
<p>
	<strong>Start year:</strong>
	<?php echo $user->start_year == 0 ? 'n/a' : $user->start_year; ?></p>
<p>
	<strong>End year:</strong>
	<?php echo $user->end_year == 0 ? 'n/a' : $user->end_year; ?></p>
<p>
	<strong>Point count:</strong>
	<?php echo $user->points; ?></p>
<p>
	<strong>Balance:</strong>
	<?php echo $user->balance; ?></p>
<p>
	<strong>Email:</strong>
	<?php echo $user->email; ?></p>

<?php 

if($user->id == Auth::get_user_id()[1]) {
	echo Html::anchor('users/edit/'.$user->id, 'Edit');
}

?>