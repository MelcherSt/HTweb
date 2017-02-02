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

// Action event
actionEvents = {
	'click .remove': function(e, value, row, index) {
	   showEnrollDeleteModal(row);
   },
   'click .edit': function(e, value, row, index) {
	   showEnrollEditModal(row);
   }
};

// Table formatter functions
function actionFormatter(value, row, index) {
	return ['<a class="edit action" href="javascript:void(0)"><span class="fa fa-pencil"><span></a>',
			'  |  ',
			'<a class="remove action" href="javascript:void(0)"><span class="fa fa-close"></span></a>'
			].join('');
}

function enrollmentFormatter(value, row, index) {
	var badges = [];
	if(row.cook) { badges.push('<span class="fa fa-cutlery"> </span>'); }
	if(row.dishwasher) { badges.push('<span class="fa fa-shower"> </span>'); }
	if(row.later) { badges.push('*'); }
	return value + ' ' + badges.join(' ');
}

function populateEnrollAddCombobox(sessionId) {
	$("#page-add-user-id-combobox").empty();
    $.get('/api/v1/sessions/' + sessionId + '/notenrolled', function(data)
    {
        $.each(data.rows, function(i,obj)
        {
            $("#page-add-user-id-combobox").append(
                 $('<option></option>')
                        .val(obj.id)
                        .html(obj.name));
        });
    });
}

function populatePayerCombobox(sessionId) {
	$("#payer-user-id-combobox").empty();
    $.get('/api/v1/sessions/' + sessionId + '/enrollments', function(data)
    {
        $.each(data.rows, function(i,obj) {
            $("#payer-user-id-combobox").append(
                 $('<option></option>')
                        .val(obj.user.id)
                        .html(obj.user.name));
        });
    });
}

function populateOnEnrollUpdate(sessionId, userId) {	
	$.get( "/api/v1/sessions/"  + sessionId + "/enrollments/" + userId, function( data ) {		
		$("#page-edit-cook").prop('checked', data.cook);	
		$("#page-edit-ater").prop('checked', data.later);
		$("#page-edit-guests").val(data.guests);
		
		if(data.cook) {
			$("#update-session-btn").show();
			$(".action-col").fadeIn(); 
		} else {
			$("#update-session-btn").hide();
			$("#page-edit-session-properties").hide('slow');
			$("#session-properties").show('slow');
			$(".action-col").fadeOut(); 
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
	$("#page-edit-session-properties").hide('slow');
	$("#session-properties").show('slow');
}

function showSessionProperties() {
	if ($("#page-edit-session-properties").is(':visible')) {
		$("#page-edit-session-properties").hide('slow');
		$("#session-properties").show('slow');
	} else {
		$("#page-edit-session-properties").show('slow');
		$("#session-properties").hide('slow');
	}
}

