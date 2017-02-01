/**
 * Sessions index
 * @author Melcher
 */

$('document').ready(function() {
	// Table click event
	$('.table-responsive').on('click-row.bs.table', function(e, row, element, field) {	
		// Browse as long as actions wasn't clicked
		if(field !== 'actions'){
			window.location = '/sessions/' + row.date;
		}
	});
	
});

function costFormatter(value, row, index) {
	return '€ ' + value;
}
