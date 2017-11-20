<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div class="row">
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>

<h4><?= __('session.stats.next_cook_msg') . ': ' . $next_cook ?></h4>

<?php
$server = $checksum['server'];
$local = $checksum['local'];

if ($server == $local && $server < 11) :
	?>
	<p class="label label-success"><span class="fa fa-check"></span> <?= __('receipt.view.point_check') ?> (<?= $server ?>)</p>
<?php else : ?>
	<p class="label label-danger"><span class="fa fa-times"></span> <?= __('receipt.view.point_check') ?> (<?= $local . '/' . $server ?>)</p>
<?php endif; ?>