<div class="panel panel-default">
	<div class="panel-heading">
		<?=__('privileges.title')?>
	</div>
	<div class="list-group">
		<?php if(\Auth::has_access('receipts.administration')) { ?>
		<a href="/receipts/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> Manage receipts</a>
		<?php } ?>
	</div>

</div>
