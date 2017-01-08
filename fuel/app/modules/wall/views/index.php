<div class="row">
	<p><?=__('user.wall.msg')?>
	<a href="/users" class="btn btn-primary pull-right"><span class="fa fa-users"></span> <?=__('user.wall.btn')?></a>
	</p>
</div>
<div class="row">
	
	<?php 
	$count = 0;
	
	foreach($users as $user) {
		$rand = rand(1,4);
		$end_year = $user->end_year;
		$start_year = $user->start_year;
		$count += 1;
		
		if ($count == 3) {
			echo '<div class="row">';
		}
	?>
	<div class="col-md-4 col-sm-4 col-xs-12 clickable">
		<div class="framed<?php echo $rand ?> portrait-container">
			<img class="img-responsive portrait" src="/users/avatar/<?=$user->id?>">
			<div class="info">
				<p class="start"><?=$start_year == 0? '' : $start_year?></p>
				<p class="end"><?=$end_year == 0? '' : $end_year?></p>
				<p class="name"><?=$user->get_fullname()?></p>
			</div>
		</div>
		<a href="/users/view/<?=$user->id?>"></a> 
	</div>
	<?php 
		if ($count == 3) {
			echo '</div>';
		}
	
	} ?>
</div>

<?php echo Asset::css('wall.css'); ?>