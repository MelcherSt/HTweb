

<?php if(isset($featured_post)): ?>
<div class="jumbotron">
	<h1><?=$featured_post->title?></h1>
	<p><?=$featured_post->get_excerpt()?></p>
	<p><a class="btn btn-success btn-lg" href="/content/view/<?=$featured_post->id?>">Read more</a></p>
</div>
<?php endif; ?>

