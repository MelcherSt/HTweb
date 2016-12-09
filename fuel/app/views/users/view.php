<p>
	<strong>Username:</strong>
	<?php echo $enrollment->username; ?></p>
<p>
	<strong>Full name:</strong>
	<?php echo $enrollment->get_fullname(); ?></p>
<p>
	<strong>Phone:</strong>
	<?php echo $enrollment->phone; ?></p>
<p>
	<strong>Start year:</strong>
	<?php echo $enrollment->start_year == 0 ? 'n/a' : $enrollment->start_year; ?></p>
<p>
	<strong>End year:</strong>
	<?php echo $enrollment->end_year == 0 ? 'n/a' : $enrollment->end_year; ?></p>
<p>
	<strong>Point count:</strong>
	<?php echo $enrollment->points; ?></p>
<p>
	<strong>Balance:</strong>
	<?php echo $enrollment->balance; ?></p>
<p>
	<strong>Email:</strong>
	<?php echo $enrollment->email; ?></p>

<?php 

if($enrollment->id == Auth::get_user_id()[1]) {
	echo '<a href="/users/edit"> <span class="fa fa-pencil"></span> Edit </a>';
	
	//echo Html::anchor('users/edit/'.$user->id, 'Edit');
}

?>