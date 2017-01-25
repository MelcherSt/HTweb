/**
 * script.js
 * @author Melcher
 */

$(document).ready(function(e) {
	// Make rows clickable while maintaing anchors
    $('.clickable-row').click(function(e) {
		// event target will always retrieve the anchor when clicked.
		if(e.target.tagName !== 'A') {
			window.location = $(this).data("href");
		}
    });
	
	// Hide IBANs by default
	$('.iban').hide();
		
	if($('#alert-success-msg').text().trim().length > 0) {
		alertSuccess();
	}
	
	if($('#alert-error-msg').text().trim().length > 0) {
		alertError();
	}
});

function alertSuccess(msg) {
	if(arguments.length === 1) {
		$('#alert-success-msg').text(msg);
	}
	
	$('#alert-success').show('slow');
	$("#alert-success").delay(2000).hide('slow');	
}


function alertError(msg) {
	if(arguments.length === 1) {
		$('#alert-error-msg').text(msg);
	}
	
	$('#alert-error').show('slow');
}

// Show iban with given id number			
function showIban($id) {				
	$('.iban-' + $id).show();
	$('.iban-show-' + $id).hide();
}
