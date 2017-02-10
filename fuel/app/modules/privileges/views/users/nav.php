<div class="panel panel-default">
	<div class="panel-heading">
		<?=__('privileges.title')?>
	</div>
	<div class="list-group">
		<?php if(\Auth::has_access('users.administration')) { ?>
		<a href="/users/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> Manage users</a>
		<?php } ?>
	</div>

</div>
