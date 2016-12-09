
<!-- Widget row -->
<div class="row">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget . '/widget')->execute();
} ?>
</div>
<!-- .Widget row -->

<div class="alert alert-info">
	<strong>Note!</strong> Het Tribunaal Web is currently in its early alpha stages and should be used for testing purposes only. 
	Expect things to break in all possible ways and not work like they should. If you spot any weird errors or mistakes, please notify Melcher.
	Feature request are very welcome, but the core features (sessions, receipts and the dashboard) have priority over others.
	
</div>