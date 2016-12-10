
<div class="row">

	<?php foreach($users as $user): 
		$rand = rand(1,4);
		$end_year = $user->end_year;
		$start_year = $user->start_year;
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
	<?php endforeach; ?>
</div>

<style>
	@font-face {
		font-family: 'Playball'; 
		src: url('/assets/fonts/Playball.ttf');
	}
	
	div.clickable a {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    text-decoration: none; 
    z-index: 10;
    background-color: #FFF; 
    opacity: 0; 
    filter: alpha(opacity=1); 
	margin-bottom: 20px;
}

div.frame-row {
	min-height: 500px;
}

.portrait-container {
	overflow: hidden;
	margin: 10px;
}

.framed1 {
	border-image: url('/assets/img/wall/frame.png') 71 stretch stretch;
	border-color: #f4be52; border-style: inset; border-width: 40px;
}

.framed2 {
	border-image: url('/assets/img/wall/frame2.png') 122 stretch stretch;
	border-color: #f4be52; border-style: inset; border-width: 40px;
}

.framed3 {
	border-image: url('/assets/img/wall/frame3.png') 90 stretch stretch;
	border-color: #f4be52; border-style: inset; border-width: 40px;
}

.framed4 {
	border-image: url('/assets/img/wall/frame4.png') 75 stretch stretch;
	border-color: #f4be52; border-style: inset; border-width: 40px;
}

.portrait {
	-webkit-filter: sepia();
	-moz-filter: sepia();
	-o-filter: sepia();
	-ms-filter: sepia();
	filter: sepia();
	max-height: 500px;
}

div.info {
	color: black;
	background-color: white;
	position: relative;
	font-family: Playball;
	font-size: 18pt;
	z-index: 0;
	overflow: wrap;
	height: 60px;
	padding: 5px;
}

p.name {
	position: relative;
	top: -10px;
	text-align: center;
	font-size: 12pt;
}

p.start {
	font-size: 10pt;
	position: absolute;
}

p.end { 
	font-size: 10pt;
	text-align: right;
}


</style>