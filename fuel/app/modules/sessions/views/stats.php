<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

<div class="row">
	<div id="distr-bar-chart" style="height: 250px; width: 100%;"></div>
</div>

<div class="row">
	<h5 class="pull-left"><?= __('session.stats.next_cook_msg') . ': ' . $next_cook ?></h5>

<?php
$server = $checksum['server'];
$local = $checksum['local'];
?>

<div class="pull-right">
<?php if ($server == $local && $server < 11) :
	?>
	<p class="label label-success"><span class="fa fa-check"></span> <?= __('receipt.view.point_check') ?> (<?= $server ?>)</p>
<?php else : ?>
	<p class="label label-danger"><span class="fa fa-times"></span> <?= __('receipt.view.point_check') ?> (<?= $local . '/' . $server ?>)</p>
<?php endif; ?>
</div>
</div>

<h3><?= __('session.stats.all_time_avg') ?></h3>
<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th class="col-md-2"><?= __('session.role.cook_plural') ?></th>
				<th class="col-md-1"><?= __('product.field.cost') ?></th>
				<th class="col-md-1">#</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach (\Sessions\Model_Session::get_avg_cook_costs() as $entry) { ?>
				<tr class="clickable-row" data-href="#">
					<td><?= $entry['name']?></td>
					<td><?= '€ ' . round($entry['avg_cost'], 2) ?></td>
					<td><?= $entry['count'] ?></td>
				</tr>
			<?php } ?>
		</tbody>	
	</table>
</div>