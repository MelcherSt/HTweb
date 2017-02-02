/**
 * Sessions view for admin
 * @author Melcher
 */

$('document').ready(function() {
	$('#add-enrollment-form').submit(function(event) {
		event.preventDefault();
		var form = $('#add-enrollment-form');	
		var table = $('#enrollments-table');
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 
				alertSuccess(form.data('alert-success'));
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action'),
			cache:false
		  });
		  table.bootstrapTable('refresh');
		  $("#add-enrollment-modal").modal('hide');
	});
	
	$('#edit-enrollment-form').submit(function(event) {
		event.preventDefault();
		var form = $('#edit-enrollment-form');	
		var table = $('#enrollments-table');
		$.ajax({
			data: form.serialize(),
			type: form.attr('method'),
			success: function() { 
				alertSuccess(form.data('alert-success'));
			},
			error: function(e){ 
				alertError(form.data('alert-error'));
			},
			url: form.attr('action') + $("#edit-user-id").val(),
			cache:false
		  });
		  table.bootstrapTable('refresh');
		  $("#edit-enrollment-modal").modal('hide');
	});
	
	$('#delete-enrollment-form').submit(function(event) {
		event.preventDefault();
		var form = $('#delete-enrollment-form');	
		var table = $('#enrollments-table');
		$.ajax({
			type: form.attr('method'),
			success: function() { 
				alertSuccess(form.data('alert-success'));
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
	
	
	$('#update-session-form').submit(function(event) {
		event.preventDefault();
		var form = $('#update-session-form');
		
		$.ajax({
			type: form.attr('method'),
			data: form.serialize(),
			success: function() { 
				alertSuccess(form.data('alert-success'));
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
	$("#add-user-id-combobox").empty();
    $.get('/api/v1/sessions/' + sessionId + '/notenrolled', function(data)
    {
        $.each(data.rows, function(i,obj)
        {
            $("#add-user-id-combobox").append(
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
        $.each(data.rows, function(i,obj)
        {
            $("#payer-user-id-combobox").append(
                 $('<option></option>')
                        .val(obj.user.id)
                        .html(obj.user.name));
        });
    });
}


function showEnrollAddModal(sessionId) {
	// Reset to default state
	$('#add-enrollment-form').trigger('reset');
	$("#add-cook").prop('disabled', false);
	$("#add-dishwasher").prop('disabled', false);
	populateEnrollAddCombobox(sessionId);
	
	// Pop modal and fill fields
	$("#add-enrollment-modal").modal();
	
	$.get( '/api/v1/sessions/'  + sessionId + "/roles", function(roles) {
		if(roles.cooks === roles.max_cooks) {
			$("#add-cook").prop('disabled', true);	
		}
		
		if(roles.dishwashers === roles.max_dishwashers) {
			$("#add-dishwasher").prop('disabled', true);
		}
	});		
}

function showEnrollDeleteModal(enrollment) {
	$("#delete-enrollment-modal").modal();
	$("#delete-user-name").html(enrollment.user.name);
	$("#delete-user-id").val(enrollment.user.id);
}

function showEnrollEditModal(enrollment) {
	// Reset to default state
	$('#edit-enrollment-form').trigger('reset');
	$("#edit-cook").prop('disabled', false);
	$("#edit-dishwasher").prop('disabled', false);
	
	// Pop modal and fill fields
	$("#edit-enrollment-modal").modal();
	$("#edit-user-name").html(enrollment.user.name);
	$("#edit-user-id").val(enrollment.user.id);
	$("#edit-guests").val(enrollment.guests);
	$("#edit-cook").prop('checked', enrollment.cook);
	$("#edit-dishwasher").prop('checked', enrollment.dishwasher);
	
	$.get( "/api/v1/sessions/"  + enrollment.session_id + "/roles", function( roles ) {
		if((roles.cooks === roles.max_cooks) && !enrollment.cook) {
				$("#edit-cook").prop('disabled', true);	
		}
		
		if((roles.dishwashers === roles.max_dishwashers) && !enrollment.dishwasher) {
				$("#edit-dishwasher").prop('disabled', true);
		}
	});	
}
