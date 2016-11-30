
<!-- Widget row -->
<div class="row">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget . '/widget')->execute();
} ?>
</div>
<!-- .Widget row -->

dashboard placeholder