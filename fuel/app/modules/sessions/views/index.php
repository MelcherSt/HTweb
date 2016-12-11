<?php

?>

<div class="row">
	<div class="col-md-4">
		<a href="/sessions/yesterday">
		<div class="panel">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-chevron-left fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">Yesterday<small></small></div>
						<div></div>
					</div>
				</div>
			</div>	
		</div>
		</a>
	</div>
	
	<div class="col-md-4">
		<a href="/sessions/today">
		<div class="panel">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-cutlery fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">Today<small></small></div>
						<div></div>
					</div>
				</div>
			</div>	
		</div>
		</a>
	</div>
	
	<div class="col-md-4">
		<a href="/sessions/tomorrow">
		<div class="panel">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-chevron-right fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge">Tomorrow<small></small></div>
						<div></div>
					</div>
				</div>
			</div>	
		</div>
		</a>
	</div>

	
</div>

<div class="row">
	<p>This list only shows unsettled sessions. For a list of settled session you participated in, please see <a href="/receipts">My Receipts</a>.</p>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Date</th>
					<th>Participants</th>
					<th>Cook(s)</th>
					<th>Dishwasher(s)</th>
					<th>Cost</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sessions as $session): ?>
				<tr class="clickable-row" data-href="/sessions/view/<?=$session->date?>">
					<td><?=$session->date?></td>
					<td><?=$session->count_total_participants()?></td>
					<td>
						<?php foreach($session->get_cook_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>	
					</td>
					<td>
						<?php foreach($session->get_dishwasher_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>
					</td>
					<td><?='€ ' . $session->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
