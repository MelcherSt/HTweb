<!-- Widget row -->
<div class="card-deck">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget)->execute();
} ?>
</div>
