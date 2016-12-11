<div class="row">
	<div class="col-md-6">

		<p>
			<strong>Username:</strong>
			<?php echo $user->username; ?></p>
		<p>
			<strong>Full name:</strong>
			<?php echo $user->get_fullname(); ?></p>
		<p>
			<strong>IBAN:</strong>
			<?php echo $user->iban; ?></p>
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
			<strong>Email:</strong>
			<?php echo $user->email; ?></p>

	</div>
	<div class="col-md-6">
		<?php
			$end_year = $user->end_year;
			$start_year = $user->start_year;
		?>
		<div class="col-md-10 hidden-xs- hidden-sm  clickable">
			<div class="framed2 portrait-container">
				<img class="img-responsive portrait" src="/users/avatar/<?=$user->id?>">
				<div class="info">
					<p class="start"><?=$start_year == 0? '' : $start_year?></p>
					<p class="end"><?=$end_year == 0? '' : $end_year?></p>
					<p class="name"><?=$user->get_fullname()?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
if($user->id == Auth::get_user_id()[1]) {
	echo '<a href="/users/edit"> <span class="fa fa-pencil"></span> Edit </a>';
}

echo Asset::css('wall.css'); ?>