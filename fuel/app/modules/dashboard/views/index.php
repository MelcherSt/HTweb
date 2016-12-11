
<div class="jumbotron">
	<h1>Welcome!</h1>
	<p>This is your dashboard. Here you'll find all information about Het Tribunaal Web you'll need.</p>
	<p><a class="btn btn-success btn-lg" href="#">Read more</a></p>
</div>

<!-- Widget row -->
<div class="row">
<?php foreach($widgets as $widget) {
	echo Request::forge($widget)->execute();
} ?>
</div>
<!-- .Widget row -->

<div class="alert alert-info">
	<strong>Note!</strong> Het Tribunaal Web is currently in its early alpha stages and should be used for testing purposes only. 
	Feature request are very welcome, but the core features (sessions, receipts and the dashboard) have priority over others.
</div>
