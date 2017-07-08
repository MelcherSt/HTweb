<!-- Widget row -->
<div class="row">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget)->execute();
} ?>
</div>

<!-- widget row -->
<div class="alert alert-info">
	<strong><?=__('alert.call.attention')?></strong>	
	<?=__('alert.msg.alpha_rel', ['product' => __('site_title') . ' ' .__('site_sub')])?>
</div>
