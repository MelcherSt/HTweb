/**
 * Sessions view for admin
 * @author Melcher
 */

$('document').ready(function() {
	var sessionId = $("#session-id").val();
	var userId = $("#user-id").val();
	var table = $('#enrollments-table');
	
	populateOnSessionUpdate(sessionId);
	populateOnEnrollUpdate(sessionId, userId);
	
	$('#add-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#add-enrollment-form-page');	
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 		
				populateOnSessionUpdate(sessionId);
				populateOnEnrollUpdate(sessionId, userId);
				toggleEnrollPanel();
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action'),
			cache:false
		  });
		  table.bootstrapTable('refresh');
	});
	
	$('#dishwasher-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#dishwasher-enrollment-form-page');	
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 	
				populateOnSessionUpdate(sessionId);
				populateOnEnrollUpdate(sessionId, userId);
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#page-edit-user-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
	});
	
	
	$('#edit-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#edit-enrollment-form-page');	
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 
				populateOnSessionUpdate(sessionId);
				populateOnEnrollUpdate(sessionId, userId);
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#page-edit-user-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
	});
	
	$('#delete-enrollment-form-page').submit(function(event) {
		event.preventDefault();
		var form = $('#delete-enrollment-form-page');	
		$.ajax({
			type: form.attr('method'),
			success: function() { 
				$("#page-add-enrollment-form").trigger('reset');
				ppopulateOnSessionUpdate(sessionId);
				populateOnEnrollUpdate(sessionId, userId);
				toggleEnrollPanel();
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#delete-user-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
	});
});

	/**
	 * Update all enrollment related UI
	 * @param {int} sessionId
	 * @param {int} userId
	 * @returns {undefined}
	 */
	function populateOnEnrollUpdate(sessionId, userId) {	
		$.ajax({
			url: '/api/v1/sessions/'  + sessionId + '/enrollments/' + userId,
			type: 'get',
			success: function(data) {
				// Set appropriate UI elements
				$("#page-edit-cook").prop('checked', data.cook);	
				$("#page-edit-later").prop('checked', data.later);
				$("#page-edit-dishwasher").prop('checked', data.dishwasher);
				$("#page-edit-guests").val(data.guests);
				
				if(data.cook) {
					$("#update-session-btn").show();
					$(".actions-col").fadeIn(); 
				} else {
					$("#update-session-btn").hide();
					toggleSessionPropertiesPanel(true);
					$(".actions-col").fadeOut(); 
				}				
				
				if($("#page-edit-cook").is(':disabled') && data.cook) {
					$("#page-edit-cook").prop('disabled', false);
				}
				
				if($("#page-edit-dishwasher").is(':disabled') && data.cook) {
					$("#page-edit-dishwasher").prop('disabled', false);
				}
				
			},
			error: function(data) {
				// Hide some UI as we are no longer enrolled.
				$("#update-session-btn").hide();
				$("#edit-session-properties-panel").hide('slow');
				$("#static-session-properties-panel").show('slow');
				$(".actions-col").fadeOut(); 
			}
		});
	}

	/**
	 * Toggle between static and editable session properties view.
	 * @param {boolean} hide Always hide panel
	 * @returns {undefined}
	 */
	function toggleSessionPropertiesPanel(hide=false) {
		if ($("#edit-session-properties-panel").is(':visible') || hide) {
			$("#edit-session-properties-panel").hide('slow');
			$("#static-session-properties-panel").show('slow');
		} else {
			$("#edit-session-properties-panel").show('slow');
			$("#static-session-properties-panel").hide('slow');
		}
	}
	
	function toggleEnrollPanel() {
		if ($("#page-add-enrollment").is(':visible')) {
			$("#page-add-enrollment").hide('slow');
			$("#page-edit-enrollment").show('slow');
		} else {
			$("#page-add-enrollment").show('slow');
			$("#page-edit-enrollment").hide('slow');
		}
	}

	

