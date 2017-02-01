/**
 * Sessions index for admin
 * @author Melcher
 */

$('document').ready(function() {
	// Table click event
	$('#sessions-table').on('click-row.bs.table', function(e, row, element, field) {	
		// Browse as long as actions wasn't clicked
		if(field !== 'actions'){
			window.location = '/sessions/admin/view/' + row.date;
		}
	});
	
	// Form submit event
	$('#delete-session-form').submit(function(event) {
		event.preventDefault();
		var form = $('#delete-session-form');
		var table = $('#sessions-table');
		$.ajax({
			type: form.attr('method'),
			success: function() { 
				alertSuccess(form.data('alert-success'));
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#delete-session-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
		  $("#delete-session-modal").modal('hide');
	});
});

// Action event
actionEvents = {
	'click .remove': function (e, value, row, index) {
	   showDeleteModal(row.id, row.date);
   }
};

// Table formatter functions
function actionFormatter(value, row, index) {
	return '<a class="remove action" href="javascript:void(0)"><span class="fa fa-trash"></span></a>';
}

function costFormatter(value, row, index) {
	return '€ ' + value;
}
		
// Modal functions
function showDeleteModal(sessionId, sessionDate) {
	$("#delete-session-date").html(sessionDate);
	$("#delete-session-id").val(sessionId);
	$("#delete-session-modal").modal();	
}