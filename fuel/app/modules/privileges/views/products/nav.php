<div class="panel panel-default">
	<div class="panel-heading">
		Privileges
	</div>
	<div class="list-group">
		<?php if(\Auth::has_access('products.management')) { ?>
		<a href="/products/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> Manage products</a>
		<?php } ?>
	</div>

</div>
