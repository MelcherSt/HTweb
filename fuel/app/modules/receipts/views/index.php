ADMIN INTERFACE <br><br>

	
	
<form method="post" action="/receipts/create">
	<div class="form-group">
		<input name="sessions" type="hidden" value="<?php foreach($sessions as $session):
	echo $session->id. ',';
endforeach;?>" />
	</div>
	<button class="btn btn-success" type="submit" ><span class="fa fa-pencil-square-o"></span> Create receipt</button>
</form>	
		

	
<?php


