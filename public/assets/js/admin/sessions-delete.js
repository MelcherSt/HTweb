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
	
	$('document').ready(function () {
		$('#delete-session-modal').on('show.bs.modal', function(e) {
			var sessionId = $(e.relatedTarget).data('session-id');
			var sessionDate = $(e.relatedTarget).data('session-date');
			$('#delete-session-id').val(sessionId);
			$('#delete-session-date').html(sessionDate);
		});	
	});
});