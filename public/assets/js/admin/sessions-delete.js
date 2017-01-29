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
			type: form.attr('method'),
			data: form.serialize(),
			success: function() { 
				alertSuccess(form.data('alert-success'));
				$('#session-' + sessionId).fadeOut();
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action'),
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