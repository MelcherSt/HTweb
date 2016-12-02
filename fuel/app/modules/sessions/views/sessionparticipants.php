<?php

$enrollments = $session->enrollments; 

?>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Id</th>
				<th>Username</th>
				<th>Guests</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($enrollments as $enrollment) {
				echo '<tr>';
				echo '<td>'.$enrollment->user->id.'</td>';
				echo '<td>'.$enrollment->user->username.'</td>';
				echo '<td>'.$enrollment->guests.'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>
<p class="pull-right">Total participants <?=$session->count_total_participants()?> 
	of which <?=$session->count_guests()?> are guests.</p>