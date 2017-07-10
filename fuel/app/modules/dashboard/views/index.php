<!-- Widget row -->
<div class="row">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget)->execute();
} ?>
</div>
