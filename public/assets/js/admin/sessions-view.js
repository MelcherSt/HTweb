/**
 * Sessions view for admin
 * @author Melcher
 */

$('document').ready(function() {
	
	var sessionId = $("#session-id").val();
	var userId = $("#user-id").val();
	
	populateOnSessionUpdate(sessionId);
		
});
