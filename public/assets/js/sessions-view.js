/**
 * Sessions view for admin
 * @author Melcher
 */

$('document').ready(function() {
	var sessionId = $("#session-id").val();
	var userId = $("#user-id").val();
	
	populateOnSessionUpdate(sessionId);
	populateOnEnrollUpdate(sessionId, userId);
	
	$('#page-add-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#page-add-enrollment-form-page');	
		var table = $('#enrollments-table');
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 
				populateOnEnrollUpdate(sessionId, userId);
				alertSuccess(form.data('alert-success'));
				$("#page-add-enrollment").hide('slow');
				$("#page-edit-enrollment").show('slow');
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action'),
			cache:false
		  });
		  table.bootstrapTable('refresh');
		  $("#page-add-enrollment-modal").modal('hide');
	});
	
	$('#page-edit-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#page-edit-enrollment-form-page');	
		var table = $('#enrollments-table');
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 
				populateOnEnrollUpdate(sessionId, userId);
				alertSuccess(form.data('alert-success'));
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#page-edit-user-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
		  $("#page-edit-enrollment-modal").modal('hide');
	});
	
	$('#delete-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#delete-enrollment-form-page');	
		var table = $('#enrollments-table');
		$.ajax({
			type: form.attr('method'),
			success: function() { 
				$("#page-add-enrollment-form").trigger('reset');
				$("#page-edit-enrollment").hide('slow');
				$("#page-add-enrollment").show('slow');
				populateOnUnroll();
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#delete-user-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
		  $("#delete-enrollment-modal").modal('hide');
	});
	
	$('#update-session-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#update-session-form-page');
		
		$.ajax({
			type: form.attr('method'),
			data: form.serialize(),
			success: function() { 
				alertSuccess(form.data('alert-success'));
				populateOnSessionUpdate(sessionId);
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

function populateOnEnrollUpdate(sessionId, userId) {	
	$.get( "/api/v1/sessions/"  + sessionId + "/enrollments/" + userId, function( data ) {		
		$("#page-edit-cook").prop('checked', data.cook);	
		$("#page-edit-later").prop('checked', data.later);
		$("#page-edit-guests").val(data.guests);
		
		if(data.cook) {
			$("#update-session-btn").show();
			$(".actions-col").fadeIn(); 
		} else {
			$("#update-session-btn").hide();
			$("#edit-session-properties-panel").hide('slow');
			$("#static-session-properties-panel").show('slow');
			$(".actions-col").fadeOut(); 
		}
	});	
}

function populateOnSessionUpdate(sessionId) {
	$.get( "/api/v1/sessions/"  + sessionId, function( data ) {	
		if(data.deadline !== "16:00") {
			$("#new-deadline").html(data.deadline);
			$("#deadline-alert").show('slow');
		} else {
			$("#deadline-alert").hide();
		}
		
		$("#session-notes").html(data.notes);
		$("#session-deadline").val(data.deadline);
		$("#session-cost").val(data.cost);
		$("#session-payer").val(data.payer.id);
		
		$("#static-session-notes").html(data.notes);
		$("#static-session-deadline").html(data.deadline);
		$("#static-session-cost").html(data.cost);
		$("#static-session-payer").html(data.payer.name);
	});	
}

function populateOnUnroll() {
	$("#update-session-btn").hide();
	$("#edit-session-properties-panel").hide('slow');
	$("#static-session-properties-panel").show('slow');
}

/**
 * Toggle between static and editable session properties view.
 * @returns {undefined}
 */
function toggleSessionPropertiesPanel() {
	if ($("#edit-session-properties-panel").is(':visible')) {
		$("#edit-session-properties-panel").hide('slow');
		$("#static-session-properties-panel").show('slow');
	} else {
		$("#edit-session-properties-panel").show('slow');
		$("#static-session-properties-panel").hide('slow');
	}
}

