<div class="row">
	<h2><?=$post->title?></h2>
	<small>written by <?=$post->author->get_fullname()?> on <?=date('Y-m-d', $post->created_at)?></small><br><br>
	<?=$post->body?>
</div>

