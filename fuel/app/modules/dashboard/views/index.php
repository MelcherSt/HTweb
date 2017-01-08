<?php if(isset($featured_post)): ?>
<div class="jumbotron">
	<h1><?=$featured_post->title?></h1>
	<p><?=$featured_post->get_excerpt()?></p>
	<p><a class="btn btn-success btn-lg" href="/content/view/<?=$featured_post->id?>"><?=__('content.post.read_more')?></a></p>
</div>
<?php endif; ?>

<!-- Widget row -->
<div class="row">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget)->execute();
} ?>
</div>
<!-- .Widget row -->

<div class="alert alert-info">
	<strong><?=__('alert.call.attention')?></strong>	
	<?=__('alert.msg.alpha_rel', ['product' => __('site_title') . ' ' .__('site_sub')])?>
</div>
