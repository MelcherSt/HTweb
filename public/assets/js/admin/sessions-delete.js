/**
 * Sessions admin delete functionality
 * @author Melcher
 */

$('document').ready(function() {
	$('#delete-session-form').submit(function(event) {
		event.preventDefault();
		var form = $('#delete-session-form');
		var sessionId = $('#delete-session-id').val();
		$.ajax({
			type: 'DELETE',
			data: form.serialize(),
			success: function() { 
				alertSuccess(LANG.session.alert.success.remove);
				$('#session-' + sessionId).fadeOut();
			},
			error: function(e){ 
				alertError(LANG.session.alert.error.remove);
			},
			url: '/sessions/admin/',
			cache:false
		  });
		  $("#delete-session-modal").modal('hide');
	});
});
		

function showDeleteModal(sessionId, sessionDate) {
	$("#delete-session-date").html(sessionDate);
	$("#delete-session-id").val(sessionId);
	$("#delete-session-modal").modal();	
}