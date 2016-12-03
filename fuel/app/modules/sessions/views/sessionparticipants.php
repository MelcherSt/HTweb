<?php

$enrollments = $session->enrollments; 

?>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Name</th>
				<th>Guests</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($enrollments as $enrollment) {
				echo '<tr>';
				echo '<td>'.$enrollment->user->name. ' ' . $enrollment->user->surname . '</td>';
				echo '<td>'.$enrollment->guests.'</td>';
				echo '</tr>';
			}
		?>
		</tbody>
	</table>
</div>
<p class="pull-right">Total participants <?=$session->count_total_participants()?> 
	of which <?=$session->count_guests()?> are guests.</p>