/**
 * Session scripts reusable between pages
 * @author Melcher
 */

// Action event handlers
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

// TODO: integrate into UI. This function currently remain unused. 
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