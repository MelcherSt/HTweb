<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$enrollments = Sessions\Model_Enrollment_Session::get_by_user($current_user->id);
?>

<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<th>Day</th>
				<th>Participants</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($enrollments as $enrollment) {
				$session = $enrollment->session;
				
				echo '<tr>';
				echo '<td>' . $session->date . '</td>';
				echo '<td>' . $session->count_total_participants() . '</td>';
				echo '<td>' . '<a href="/sessions/view/' . $session->date . '"> View </a> </td>';
				echo '</tr>';
				
			}
		?>
		</tbody>
	</table>
</div>


