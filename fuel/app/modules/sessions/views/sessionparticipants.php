<?php

$enrollments = $session->enrollments; 
$participant_count = 0;

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
				$participant_count += 1 + $enrollment->guests;
			}
		?>
		</tbody>
	</table>
</div>
<p class="pull-right">Total participants: <?=$participant_count?> </p>