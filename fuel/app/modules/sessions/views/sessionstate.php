<?php

// Change view based on enrollment state

$enrollment = $session->current_enrollment();

if(isset($enrollment)) {
	echo 'enrolled';
}

?>

<form action="/sessions/view/<?=$session->date?>" method="post" >
	<input type="submit" value="Submit">
</form> 

<?php

if ($session->can_enroll()) {
	echo 'Can enroll';
} else {
	echo 'Can no longer enroll';
}


