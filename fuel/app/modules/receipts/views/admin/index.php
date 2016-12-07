<p>On this page you are able to create receipts. Essentialy this will archive the products and/or sessions in the system, calculates the point and money deltas and stores them.
In princple a receipt can be made undone.
</p>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Id</th>
				<th>Date</th>
				<th>Notes</th>
				<th>Total participants</th>
				<th>Cost</th>
			</tr>
		</thead>
		<tbody>
		<?php 			
			foreach($sessions as $session): ?>
			<tr>
				<td><?=$session->id?></td>
				<td><?=$session->date?></td>
				<td><?=$session->notes?></td>
				<td><?=$session->count_total_paritipants()?></td>
				<td><?=$session->cost?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
	
<form method="post" action="/receipts/create">
	<div class="form-group">
		<input name="sessions" type="hidden" value="<?php foreach($sessions as $session):
	echo $session->id. ',';
endforeach;?>" />
	</div>
	<button class="btn btn-success" type="submit" ><span class="fa fa-pencil-square-o"></span> Create receipt</button>
</form>	
		

	
<?php


